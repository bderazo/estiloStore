<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\PointTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    /**
     * Crear una nueva reserva/pedido
     */
    public function crearReserva(User $user, array $items)
    {
        // 1. Regla de negocio: Mínimo 4 artículos
        $totalArticulos = collect($items)->sum('cantidad');
        if ($totalArticulos < 4) {
            throw new \Exception("La reserva debe tener al menos 4 artículos. Actualmente tiene: {$totalArticulos}");
        }

        // 2. Verificar si el usuario está bloqueado
        if (!$user->active) {
            throw new \Exception("Tu cuenta está bloqueada para realizar nuevas reservas.");
        }

        return DB::transaction(function () use ($user, $items) {
            // Calcular fecha límite: Próximo domingo 12:00 PM
            $fechaLimite = Carbon::now()->next(Carbon::SUNDAY)->setTime(12, 0, 0);

            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0, // Se actualizará al sumar los detalles
                'estado' => 'pendiente_abono',
                'tipo' => 'reserva',
                'fecha_limite' => $fechaLimite
            ]);

            $montoTotal = 0;

            foreach ($items as $item) {
                // $item debe contener: articulo_id, articulo_variante_id, cantidad
                
                // Verificar stock en la tabla legacy 'articulo_variantes'
                $variante = DB::table('articulo_variantes')->where('id', $item['articulo_variante_id'])->first();

                if (!$variante || $variante->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para uno de los productos seleccionados.");
                }

                // Restar stock
                DB::table('articulo_variantes')
                    ->where('id', $item['articulo_variante_id'])
                    ->decrement('stock', $item['cantidad']);

                $articulo = DB::table('articulos')->find($item['articulo_id']);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'articulo_id' => $item['articulo_id'],
                    'articulo_variante_id' => $item['articulo_variante_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $articulo->precio
                ]);

                $montoTotal += ($articulo->precio * $item['cantidad']);
            }

            $order->update(['total' => $montoTotal]);

            return $order;
        });
    }

    /**
     * Registrar un pago (Subida de Voucher)
     */
    public function registrarPago(Order $order, $monto, $file)
    {
        $path = $file->store('vouchers', 'public');

        return OrderPayment::create([
            'order_id' => $order->id,
            'monto' => $monto,
            'comprobante_url' => $path,
            'estado' => 'pendiente'
        ]);
    }

    /**
     * Validación por parte del Técnico
     */
    public function validarPagoTecnico($paymentId, $aprobado, $observaciones = null)
    {
        return DB::transaction(function () use ($paymentId, $aprobado, $observaciones) {
            $payment = OrderPayment::findOrFail($paymentId);
            $order = $payment->order;

            if ($aprobado) {
                $payment->update([
                    'estado' => 'aprobado',
                    'tecnico_id' => auth()->id()
                ]);

                // Verificar si con este abono se completa el total
                $totalPagado = $order->payments()->where('estado', 'aprobado')->sum('monto');

                if ($totalPagado >= $order->total) {
                    $order->update(['estado' => 'completado']);
                    $this->asignarPuntosPorCompra($order);
                } else {
                    $order->update(['estado' => 'pendiente_pago_total']);
                }
            } else {
                $payment->update([
                    'estado' => 'rechazado',
                    'observaciones' => $observaciones,
                    'tecnico_id' => auth()->id()
                ]);
            }
        });
    }

    /**
     * Proceso de limpieza dominical (Cron Job)
     */
    public function procesarOrdenesExpiradas()
    {
        $expiradas = Order::whereIn('estado', ['pendiente_abono', 'pendiente_pago_total', 'validando_pago'])
            ->where('fecha_limite', '<', Carbon::now())
            ->get();

        $procesadas = 0;

        foreach ($expiradas as $order) {
            DB::transaction(function () use ($order) {
                // 1. Devolver Stock a las variantes legacy
                foreach ($order->details as $detail) {
                    DB::table('articulo_variantes')
                        ->where('id', $detail->articulo_variante_id)
                        ->increment('stock', $detail->cantidad);
                }

                // 2. Marcar orden como cancelada
                $order->update(['estado' => 'cancelado']);

                // 3. Penalizar al usuario
                $user = $order->user;
                $user->increment('fallos_reserva');

                // Si llega a 2 fallos, se bloquea la cuenta
                if ($user->fallos_reserva >= 2) {
                    $user->update(['active' => false]);
                }
            });
            $procesadas++;
        }

        return $procesadas;
    }

    /**
     * Asignación de puntos (1 punto por cada $1 dólar)
     */
    private function asignarPuntosPorCompra(Order $order)
    {
        $puntos = floor($order->total);
        
        if ($puntos > 0) {
            $user = $order->user;
            $user->increment('puntos_acumulados', $puntos);

            PointTransaction::create([
                'user_id' => $user->id,
                'cantidad' => $puntos,
                'motivo' => "Compra completada #" . $order->id,
                'reference_type' => Order::class,
                'reference_id' => $order->id
            ]);

            $order->update(['puntos_generados' => $puntos]);
        }
    }
}
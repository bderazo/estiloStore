<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\PedidoPagoResource;
use App\Models\Pedido;
use App\Models\PedidoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPedidoController extends Controller
{

    public function index(Request $request)
    {
        $query = Pedido::with(['user', 'transporte', 'pagos'])
            ->latest();
        
        // Aplicar filtros
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('codigo')) {
            $query->where('codigo_reserva', 'like', '%' . $request->codigo . '%');
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        
        if (in_array($sortBy, ['created_at', 'total', 'codigo_reserva'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        // Paginación
        $perPage = $request->get('per_page', 20);
        $pedidos = $query->paginate($perPage);
        
        return PedidoResource::collection($pedidos)->additional([
            'meta' => [
                'current_page' => $pedidos->currentPage(),
                'per_page' => $pedidos->perPage(),
                'total' => $pedidos->total(),
                'last_page' => $pedidos->lastPage(),
                'from' => $pedidos->firstItem(),
                'to' => $pedidos->lastItem(),
            ]
        ]);
    }

    public function show($id)
    {
        $pedido = Pedido::with([
            'user',
            'transporte',
            'pagos' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'detalles.variante.articulo',
            'detalles.variante.color',
            'detalles.variante.talla'
        ])->findOrFail($id);
        
        return new PedidoResource($pedido);
    }

    public function pagos($id)
    {
        $pagos = PedidoPago::where('pedido_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return PedidoPagoResource::collection($pagos);
    }

    public function estados()
    {
        $estados = Pedido::select('estado')
            ->distinct()
            ->pluck('estado');
        
        return response()->json($estados);
    }

    public function pagosPendientesCount()
    {
        $count = PedidoPago::where('estado', 'pendiente')->count();
        
        return response()->json(['count' => $count]);
    }

    public function pagosPendientes(Request $request)
    {
        $query = PedidoPago::with(['pedido.user', 'pedido.transporte'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'asc');

        $perPage = $request->get('per_page', 20);
        $pagos = $query->paginate($perPage);
        
        return response()->json([
            'data' => PedidoPagoResource::collection($pagos),
            'meta' => [
                'current_page' => $pagos->currentPage(),
                'per_page' => $pagos->perPage(),
                'total' => $pagos->total(),
                'last_page' => $pagos->lastPage(),
                'from' => $pagos->firstItem(),
                'to' => $pagos->lastItem(),
            ]
        ]);
    }

    public function aprobarPago(Request $request, $id)
    {
        $request->validate([
            'observacion' => 'nullable|string|max:500'
        ]);
        
        \Log::info('=== INICIANDO APROBACIÓN DE PAGO ===');
        \Log::info('Pago ID: ' . $id);
        
        // 1. Buscar el pago
        $pago = PedidoPago::findOrFail($id);
        \Log::info('Pago encontrado:', $pago->toArray());
        
        // 2. Verificar que el pago esté pendiente
        if ($pago->estado !== 'pendiente') {
            \Log::warning('Pago no está pendiente. Estado actual: ' . $pago->estado);
            return response()->json([
                'success' => false,
                'message' => 'Este pago ya ha sido procesado anteriormente'
            ], 400);
        }
        
        // 3. Buscar el pedido asociado
        $pedido = $pago->pedido;
        if (!$pedido) {
            \Log::error('No se encontró el pedido asociado al pago ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }
        
        \Log::info('Pedido encontrado:', $pedido->toArray());
        
        // 4. Cambiar estado del pago a 'aprobado'
        $pago->update([
            'estado' => 'aprobado',
            'observacion' => $request->observacion ?? 'Pago aprobado por administrador'
        ]);
        
        \Log::info('Pago actualizado a aprobado');
        
        // 5. Calcular totales
        $totalAPagar = $pedido->total + $pedido->costo_envio;
        
        // Suma de pagos aprobados (incluyendo el que acabamos de aprobar)
        $totalPagado = PedidoPago::where('pedido_id', $pedido->id)
            ->where('estado', 'aprobado')
            ->sum('monto');
        
        \Log::info("Total a pagar: {$totalAPagar}, Total pagado: {$totalPagado}");
        
        // 6. Determinar nuevo estado del pedido
        $nuevoEstadoPedido = null;
        
        if ($totalPagado >= $totalAPagar) {
            $nuevoEstadoPedido = 'completado';
            \Log::info('Estado del pedido: COMPLETADO (pago total o excedente)');
        } elseif ($totalPagado > 0) {
            $nuevoEstadoPedido = 'abonado';
            \Log::info('Estado del pedido: ABONADO (pago parcial)');
        } else {
            $nuevoEstadoPedido = 'pendiente';
            \Log::info('Estado del pedido: PENDIENTE (sin pagos aprobados)');
        }
        
        // 7. Actualizar estado del pedido si cambió
        if ($pedido->estado !== $nuevoEstadoPedido) {
            $pedido->update(['estado' => $nuevoEstadoPedido]);
            \Log::info("Estado del pedido actualizado de '{$pedido->estado}' a '{$nuevoEstadoPedido}'");
        }
        
        // 8. Registrar en logs
        \Log::info("Pago ID {$id} aprobado exitosamente");
        \Log::info("Monto: {$pago->monto}");
        \Log::info("Pedido ID: {$pedido->id}");
        \Log::info("Nuevo estado pedido: {$nuevoEstadoPedido}");
        \Log::info('=== FIN APROBACIÓN DE PAGO ===');
        
        return response()->json([
            'success' => true,
            'message' => 'Pago aprobado correctamente',
            'data' => [
                'pago' => $pago,
                'pedido' => $pedido->fresh(), // Recargar datos actualizados
                'total_pagado' => $totalPagado,
                'total_a_pagar' => $totalAPagar,
                'nuevo_estado_pedido' => $nuevoEstadoPedido
            ]
        ]);
    }

    public function rechazarPago(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|min:5|max:500'
        ]);
        
        \Log::info('=== INICIANDO RECHAZO DE PAGO ===');
        \Log::info('Pago ID: ' . $id);
        \Log::info('Motivo: ' . $request->motivo);
        
        // 1. Buscar el pago
        $pago = PedidoPago::findOrFail($id);
        \Log::info('Pago encontrado:', $pago->toArray());
        
        // 2. Verificar que el pago esté pendiente
        if ($pago->estado !== 'pendiente') {
            \Log::warning('Pago no está pendiente. Estado actual: ' . $pago->estado);
            return response()->json([
                'success' => false,
                'message' => 'Este pago ya ha sido procesado anteriormente'
            ], 400);
        }
        
        // 3. Cambiar estado del pago a 'rechazado'
        $pago->update([
            'estado' => 'rechazado',
            'observacion' => $request->motivo
        ]);
        
        \Log::info('Pago actualizado a rechazado');
        
        // 4. Obtener el pedido para recalcular estado si es necesario
        $pedido = $pago->pedido;
        
        if ($pedido) {
            // 5. Recalcular si hay pagos aprobados
            $totalPagado = PedidoPago::where('pedido_id', $pedido->id)
                ->where('estado', 'aprobado')
                ->sum('monto');
            
            $totalAPagar = $pedido->total + $pedido->costo_envio;
            
            \Log::info("Después de rechazar - Total pagado: {$totalPagado}, Total a pagar: {$totalAPagar}");
            
            // 6. Determinar nuevo estado del pedido
            $nuevoEstadoPedido = null;
            
            if ($totalPagado >= $totalAPagar) {
                $nuevoEstadoPedido = 'completado';
            } elseif ($totalPagado > 0) {
                $nuevoEstadoPedido = 'abonado';
            } else {
                $nuevoEstadoPedido = 'pendiente';
            }
            
            // 7. Actualizar si cambió el estado
            if ($pedido->estado !== $nuevoEstadoPedido) {
                $pedido->update(['estado' => $nuevoEstadoPedido]);
                \Log::info("Estado del pedido actualizado a: {$nuevoEstadoPedido}");
            }
        }
        
        \Log::info('Pago rechazado exitosamente');
        \Log::info('=== FIN RECHAZO DE PAGO ===');
        
        return response()->json([
            'success' => true,
            'message' => 'Pago rechazado correctamente',
            'data' => $pago
        ]);
    }

    public function cambiarEstadoPedido(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,abonado,completado,rechazado,cancelado',
            'observacion' => 'nullable|string|max:500'
        ]);
        
        \Log::info('=== CAMBIO MANUAL DE ESTADO PEDIDO ===');
        \Log::info('Pedido ID: ' . $id);
        \Log::info('Nuevo estado: ' . $request->estado);
        
        $pedido = Pedido::findOrFail($id);
        
        // Guardar estado anterior
        $estadoAnterior = $pedido->estado;
        
        // Actualizar estado
        $pedido->update([
            'estado' => $request->estado,
            'observacion' => $request->observacion
        ]);
        
        \Log::info("Estado cambiado de '{$estadoAnterior}' a '{$request->estado}'");
        \Log::info('=== FIN CAMBIO MANUAL ===');
        
        return response()->json([
            'success' => true,
            'message' => 'Estado del pedido actualizado correctamente',
            'data' => [
                'pedido' => $pedido,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $request->estado
            ]
        ]);
    }

    public function estadisticas()
    {
        $totalPedidos = Pedido::count();
        $pedidosPendientes = Pedido::where('estado', 'pending')->count();
        $pedidosPagados = Pedido::where('estado', 'paid')->count();
        $pedidosEnProceso = Pedido::where('estado', 'processing')->count();
        
        // Total recaudado (solo pagos aprobados)
        $totalRecaudado = PedidoPago::where('estado', 'aprobado')->sum('monto');
        
        // Pedidos por estado
        $pedidosPorEstado = Pedido::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get()
            ->pluck('total', 'estado');
        
        // Pagos por estado
        $pagosPorEstado = PedidoPago::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get()
            ->pluck('total', 'estado');
        
        // Ventas por mes (últimos 6 meses)
        $ventasPorMes = Pedido::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(total) as total_ventas')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        
        return response()->json([
            'total_pedidos' => $totalPedidos,
            'pedidos_pendientes' => $pedidosPendientes,
            'pedidos_pagados' => $pedidosPagados,
            'pedidos_en_proceso' => $pedidosEnProceso,
            'total_recaudado' => $totalRecaudado,
            'pedidos_por_estado' => $pedidosPorEstado,
            'pagos_por_estado' => $pagosPorEstado,
            'ventas_por_mes' => $ventasPorMes
        ]);
    }

    private function actualizarEstadoPedido($pedido)
    {
        $totalPagado = $pedido->pagos()
            ->where('estado', 'aprobado')
            ->sum('monto');
        
        $totalAPagar = $pedido->total + $pedido->costo_envio;
        
        if ($totalPagado >= $totalAPagar) {
            $pedido->update(['estado' => 'paid']);
        } elseif ($totalPagado > 0) {
            $pedido->update(['estado' => 'partial']);
        } else {
            $pedido->update(['estado' => 'pending']);
        }
    }
}
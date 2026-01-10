<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\PointTransaction;

class CustomerService
{
    /**
     * Obtiene el resumen de puntos y estadísticas para el dashboard
     */
    public function getDashboardStats(User $user)
    {
        return [
            'puntos_actuales' => $user->puntos_acumulados ?? 0,
            'total_reservas'  => $user->orders()->where('tipo', 'reserva')->count(),
            'total_compras'   => $user->orders()->where('estado', 'completado')->count(),
            'fallos'          => $user->fallos_reserva,
            'estado_cuenta'   => $user->active ? 'Activa' : 'Bloqueada',
        ];
    }

    /**
     * Historial de puntos detallado
     */
    public function getPuntosHistorial(User $user)
    {
        return PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtiene órdenes que requieren atención (subir voucher)
     */
    public function getOrdenesPendientes(User $user)
    {
        return Order::where('user_id', $user->id)
            ->whereIn('estado', ['pendiente_abono', 'pendiente_pago_total'])
            ->with('details.articulo')
            ->orderBy('fecha_limite', 'asc')
            ->get();
    }
}
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderPaymentController extends Controller
{
    public function upload(Request $request, Order $order, OrderService $orderService)
    {
        $request->validate([
            'monto' => 'required|numeric|min:1',
            'comprobante' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Verificamos que la orden pertenezca al usuario
            if ($order->user_id !== auth()->id()) abort(403);

            $orderService->registrarPago($order, $request->monto, $request->file('comprobante'));

            return back()->with('success', 'Comprobante subido con éxito. Un técnico lo validará pronto.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir comprobante: ' . $e->getMessage());
        }
    }
}
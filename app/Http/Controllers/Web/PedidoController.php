<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PedidoController extends Controller
{
    // Listado de todas mis reservas
    public function index()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('web.pedidos.index', compact('pedidos'));
    }

    // Detalle de una reserva específica y formulario de abono
    public function show($id)
    {
        $pedido = Pedido::with(['detalles.variante.articulo', 'pagos'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('web.pedidos.show', compact('pedido'));
    }

    // Lógica para subir el vaucher (Abono)
    public function subirPago(Request $request, $id)
    {
        
        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'comprobante' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $pedido = Pedido::where('user_id', auth()->id())->findOrFail($id);

        if ($request->hasFile('comprobante')) {
            // Guardar imagen en storage/app/public/vauchers
            $path = $request->file('comprobante')->store('vauchers', 'public');

            PedidoPago::create([
                'pedido_id' => $pedido->id,
                'monto' => $request->monto,
                'comprobante_ruta' => $path,
                'estado' => 'pendiente' // Esperando validación del técnico
            ]);

            return back()->with('success', 'Tu comprobante ha sido enviado. Un técnico lo validará pronto.');
        }

        return back()->with('error', 'Error al subir el archivo.');
    }
}
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use App\Models\Pedido;
use App\Models\PedidoPago;
use App\Models\Transporte;
use App\Models\DireccionEntrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        $metodos = MetodoPago::where('activo', 1)->get();
        $isLogged = auth()->check();
        return view('web.pedidos.index', compact('pedidos', 'metodos', 'isLogged'));
    }


    public function show($id)
    {
        $pedido = Pedido::with(['detalles.variante.articulo', 'pagos', 'transporte'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Obtenemos todas las rutas disponibles
        $rutas = Transporte::orderBy('ruta', 'asc')->get();
        $metodosPago = MetodoPago::where('activo', 1)->get();

        return view('web.pedidos.show', compact('pedido', 'rutas', 'metodosPago'));
    }

    public function asignarTransporte(Request $request, $id)
    {
        $request->validate([
            'transporte_id' => 'required|exists:transportes,id'
        ]);

        $pedido = Pedido::where('user_id', auth()->id())->findOrFail($id);
        $transporte = \App\Models\Transporte::findOrFail($request->transporte_id);

        $pedido->update([
            'transporte_id' => $transporte->id,
            'costo_envio' => $transporte->precio
        ]);

        return response()->json([
            'success' => true,
            'subtotal_productos' => number_format($pedido->total, 2),
            'costo_envio' => number_format($pedido->costo_envio, 2),
            'total_final' => number_format($pedido->total + $pedido->costo_envio, 2),
            'cooperativa' => $transporte->cooperativa
        ]);
    }

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

    public function guardarDireccion(Request $request)
    {
        try {
            $request->validate([
                'pedido_id' => 'required|exists:pedidos,id',
                'barrio' => 'required|string|max:255',
                'calle_principal' => 'required|string|max:255',
                'calle_secundaria' => 'required|string|max:255',
                'color_casa' => 'nullable|string|max:100',
                'referencia' => 'nullable|string|max:500'
            ]);

            $pedido = Pedido::with('transporte')->findOrFail($request->pedido_id);

            // Verificar que sea QUEVEDO
            if (!$pedido->transporte || !str_contains($pedido->transporte->ruta, 'QUEVEDO')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta ruta no requiere dirección detallada'
                ], 400);
            }

            // Actualizar o crear la dirección
            $direccion = DireccionEntrega::updateOrCreate(
                ['pedido_id' => $pedido->id],
                [
                    'barrio' => $request->barrio,
                    'calle_principal' => $request->calle_principal,
                    'calle_secundaria' => $request->calle_secundaria,
                    'color_casa' => $request->color_casa,
                    'referencia' => $request->referencia
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Dirección guardada correctamente',
                'data' => $direccion
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error guardando dirección: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la dirección'
            ], 500);
        }
    }
}
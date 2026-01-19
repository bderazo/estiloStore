<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\ArticuloVariante;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Services\CarritoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarritoController extends Controller
{
    protected $carritoService;

    public function __construct(CarritoService $service)
    {
        $this->carritoService = $service;
    }

    public function index()
    {
        $items = $this->carritoService->getCarrito();
        $totales = $this->carritoService->calcularTotales();
        $tienePedidosCompletados = Pedido::where('user_id', auth()->id())
            ->where('estado', 'completado')
            ->exists();
        if ($tienePedidosCompletados) {
            $totales['puede_pagar'] = count($items) > 0;
            $totales['es_cliente_antiguo'] = true;
        } else {
            $totales['puede_pagar'] = ($totales['cantidad_items'] ?? 0) >= 4;
            $totales['es_cliente_antiguo'] = false;
        }
        return view('web.carrito.index', compact('items', 'totales'));
    }

    public function buscar(Request $request)
    {
        $sku = $request->codigo;
        // Buscamos el artículo con sus variantes, colores y tallas
        $articulo = Articulo::with(['variantes.color', 'variantes.talla'])
            ->where('sku', 'LIKE', "%{$sku}%")
            ->first();

        if (!$articulo) {
            return response()->json(['error' => 'Producto no encontrado en el catálogo'], 404);
        }

        $carrito = session()->get('carrito', []);
        $imagenPrincipal = '';
        // Procesamos cada variante individualmente
        $variantesDisponibles = $articulo->variantes->map(function ($v) use ($carrito) {
            // Calculamos cuánto hay de ESTA variante específica en el carrito
            $enCarrito = isset($carrito[$v->id]) ? $carrito[$v->id]['cantidad'] : 0;
            $stockRealEfectivo = $v->stock - $enCarrito;

            return [
                'id' => $v->id,
                'color' => $v->color->nombre ?? 'Único',
                'talla' => $v->talla->nombre ?? 'Único',
                'stock_visual' => max(0, $stockRealEfectivo),
                'precio' => $v->articulo->precio_oferta ?? $v->articulo->precio,
            ];
        })->filter(fn($v) => $v['stock_visual'] > 0); // Solo mostramos lo que aún se puede añadir

        $imagenPrincipal = $articulo->getImagenPrincipalAttribute() ? asset('storage/articulos/' . $articulo->id . '/' . $articulo->getImagenPrincipalAttribute()) : '';

        return response()->json([
            'nombre' => $articulo->nombre,
            'imagen' => $imagenPrincipal,
            'variantes' => $variantesDisponibles->values()
        ]);
    }

    public function agregar(Request $request)
    {
        
        $varianteId = $request->input('variante_id');
        if (!$varianteId) {
            // Si no viene el ID directo (caso Detalle), lo buscamos por la combinación
            $request->validate([
                'articulo_id' => 'required|exists:articulos,id',
                'color_id'    => 'nullable|exists:colores,id',
                'talla_id'    => 'nullable|exists:tallas,id',
            ]);

            $variante = ArticuloVariante::where('articulo_id', $request->articulo_id)
                ->when($request->color_id, function ($query, $colorId) {
                    return $query->where('color_id', $colorId);
                })
                ->when($request->talla_id, function ($query, $tallaId) {
                    return $query->where('talla_id', $tallaId);
                })
                ->first();

            if (!$variante) {
                return back()->with('error', 'La combinación de color y talla seleccionada no existe.');
            }

            $varianteId = $variante->id;
        }

        $variante = ArticuloVariante::with('articulo')->find($varianteId);
        $cantidadAAgregar = intval($request->cantidad);

        $carrito = session()->get('carrito', []);

        $actualEnCarrito = isset($carrito[$variante->id]) ? $carrito[$variante->id]['cantidad'] : 0;

        // Validación: Sumamos lo que ya tiene + lo que quiere agregar ahora
        if (($actualEnCarrito + $cantidadAAgregar) > $variante->stock) {
            return response()->json([
                'error' => "Solo puedes agregar " . ($variante->stock - $actualEnCarrito) . " unidades más de esta variante."
            ], 422);
        }

        if (isset($carrito[$variante->id])) {
            $carrito[$variante->id]['cantidad'] += $cantidadAAgregar;
        } else {
            $carrito[$variante->id] = [
                'id'        => $variante->id,
                'nombre'    => $variante->articulo->nombre,
                'imagen'    => $variante->articulo->getImagenPrincipalAttribute() ? asset('storage/articulos/' . $variante->articulo_id . '/' . $variante->articulo->getImagenPrincipalAttribute()) : '',
                'precio'    => $variante->articulo->precio_oferta ?? $variante->articulo->precio,
                'color'     => $variante->color->nombre ?? 'N/A',
                'talla'     => $variante->talla->nombre ?? 'N/A',
                'cantidad'  => $cantidadAAgregar
            ];
        }

        session()->put('carrito', $carrito);
        return response()->json(['success' => true]);
    }
    // 1. FUNCIONALIDAD: ELIMINAR DEL CARRITO
    public function eliminar(Request $request) {
        $this->carritoService->eliminar($request->variante_id);
        return response()->json(['success' => 'Artículo eliminado', 'totales' => $this->carritoService->calcularTotales()]);
    }

    // 2. PROCESAR RESERVA (PAGAR)
    public function procesarReserva(Request $request) {
        $errores = $this->carritoService->validarStockDisponible();
        
        if (!empty($errores)) {
            return response()->json(['error' => 'Stock insuficiente', 'detalles' => $errores], 422);
        }

        DB::beginTransaction();
        try {
            $carrito = $this->carritoService->getCarrito();
            $totales = $this->carritoService->calcularTotales();

            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'codigo_reserva' => 'RES-' . strtoupper(Str::random(8)),
                'subtotal' => $totales['subtotal'],
                'total' => $totales['subtotal'], // Ajustar si hay impuestos
                'estado' => 'pendiente'
            ]);

            foreach ($carrito as $item) {
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'variante_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio']
                ]);

                // DEBITAR DEL STOCK
                $variante = ArticuloVariante::find($item['id']);
                $variante->decrement('stock', $item['cantidad']);
            }

            $this->carritoService->vaciar();
            DB::commit();

            return response()->json(['success' => 'Reserva creada', 'pedido_id' => $pedido->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar reserva'], 500);
        }
    }
}

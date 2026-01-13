<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\ArticuloVariante;
use App\Services\CarritoService;
use Illuminate\Http\Request;

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
                'precio' => $v->articulo->precio_oferta??$v->articulo->precio,
                'imagen' => $v->articulo->imagen
            ];
        })->filter(fn($v) => $v['stock_visual'] > 0); // Solo mostramos lo que aún se puede añadir

        return response()->json([
            'nombre' => $articulo->nombre,
            'variantes' => $variantesDisponibles->values()
        ]);
    }

public function agregar(Request $request)
{
    $variante = ArticuloVariante::with('articulo')->find($request->variante_id);
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
            'id' => $variante->id,
            'nombre' => $variante->articulo->nombre,
            'imagen' => $variante->articulo->imagen,
            'precio' => $variante->articulo->precio_oferta ?? $variante->articulo->precio,
            'color' => $variante->color->nombre ?? 'N/A',
            'talla' => $variante->talla->nombre ?? 'N/A',
            'cantidad' => $cantidadAAgregar
        ];
    }

    session()->put('carrito', $carrito);
    return response()->json(['success' => true]);
}
}

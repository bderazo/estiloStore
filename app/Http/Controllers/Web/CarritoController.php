<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Services\CarritoService;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    protected $carritoService;

    public function __construct(CarritoService $service) {
        $this->carritoService = $service;
    }

    public function index() {
        $items = $this->carritoService->getCarrito();
        $totales = $this->carritoService->calcularTotales();
        return view('web.carrito.index', compact('items', 'totales'));
    }

    // Buscador rápido para el catálogo manual
    public function buscarPorCodigo(Request $request) {
        $articulo = Articulo::with('variantes.color', 'variantes.talla')
            ->where('codigo', $request->codigo)
            ->first();

        if (!$articulo) return response()->json(['error' => 'Código no encontrado'], 404);

        return response()->json([
            'id' => $articulo->id,
            'nombre' => $articulo->nombre,
            'variantes' => $articulo->variantes
        ]);
    }
}
<?php

namespace App\Services;

use App\Models\ArticuloVariante;
use Illuminate\Support\Facades\Session;

class CarritoService
{
    public function getCarrito()
    {
        return Session::get('carrito', []);
    }

    public function agregar($varianteId, $cantidad)
    {
        $variante = ArticuloVariante::with('articulo', 'color', 'talla')->findOrFail($varianteId);
        $carrito = $this->getCarrito();
        if (isset($carrito[$varianteId])) {
            $carrito[$varianteId]['cantidad'] += $cantidad;
        } else {
            $carrito[$varianteId] = [
                'variante_id' => $variante->id,
                'nombre' => $variante->articulo->nombre,
                'color' => $variante->color->nombre ?? 'N/A',
                'talla' => $variante->talla->nombre ?? 'N/A',
                'precio' => $variante->articulo->precio_venta,
                'cantidad' => $cantidad,
                'imagen' => $variante->articulo->imagenPrincipal()
            ];
        }

        Session::put('carrito', $carrito);
    }

    public function calcularTotales()
    {
        $carrito = $this->getCarrito();
        $subtotal = collect($carrito)->sum(fn($i) => $i['precio'] * $i['cantidad']);
        $cantidad_items = collect($carrito)->sum('cantidad');
        
        return [
            'subtotal' => $subtotal,
            'puntos' => floor($subtotal),
            'cantidad_items' => $cantidad_items,
            'puede_pagar' => $cantidad_items >= 4
        ];
    }
    // 1. FUNCIONALIDAD: ELIMINAR ARTÍCULO
    public function eliminar($varianteId) {
        $carrito = $this->getCarrito();
        if (isset($carrito[$varianteId])) {
            unset($carrito[$varianteId]);
            Session::put('carrito', $carrito);
        }
        return $carrito;
    }

    // VALIDACIÓN DE STOCK ANTES DE RESERVAR
    public function validarStockDisponible() {
        $carrito = $this->getCarrito();
        $errores = [];

        foreach ($carrito as $item) {
            $variante = ArticuloVariante::find($item['id']);
            if (!$variante || $variante->stock < $item['cantidad']) {
                $stockActual = $variante ? $variante->stock : 0;
                $errores[] = "El artículo '{$item['nombre']}' solo tiene {$stockActual} unidades disponibles.";
            }
        }
        return $errores;
    }

    public function vaciar() {
        Session::forget('carrito');
    }
}
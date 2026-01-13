<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $items = session('carrito', []);

        // Si el carrito está vacío, lo mandamos de vuelta con un mensaje
        if (empty($items)) {
            //return redirect()->route('web.carrito.index')->with('info', 'Tu carrito está vacío.');
        }

        $metodos = MetodoPago::where('activo', 1)->get();

        $total = collect($items)->sum(fn($i) => $i['precio'] * $i['cantidad']);

        // Pasamos una variable booleana para saber si está logueado
        $isLogged = auth()->check();

        return view('web.pagos.index', compact('metodos', 'total', 'isLogged'));
    }
}

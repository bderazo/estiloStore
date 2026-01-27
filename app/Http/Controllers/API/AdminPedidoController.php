<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function index(Request $request)
    {
        $query = Pedido::with(['user', 'transporte', 'pagos'])
            ->latest();
        
        // Filtros
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
        
        $pedidos = $query->paginate(20);
        
        $estados = Pedido::select('estado')
            ->distinct()
            ->pluck('estado');
        
        return view('admin.pedidos.index', compact('pedidos', 'estados'));
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
        
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function aprobarPago(Request $request, $id)
    {
        $pago = PedidoPago::findOrFail($id);
        
        $pago->update([
            'estado' => 'aprobado',
            'observacion' => $request->observacion
        ]);
        
        // Verificar si el pedido está completamente pagado
        $pedido = $pago->pedido;
        $totalPagado = $pedido->pagos()
            ->where('estado', 'aprobado')
            ->sum('monto');
        
        $totalAPagar = $pedido->total + $pedido->costo_envio;
        
        if ($totalPagado >= $totalAPagar) {
            $pedido->update(['estado' => 'pagado']);
        } else {
            $pedido->update(['estado' => 'parcial']);
        }
        
        return back()->with('success', 'Pago aprobado correctamente.');
    }

    public function rechazarPago(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|min:5'
        ]);
        
        $pago = PedidoPago::findOrFail($id);
        
        $pago->update([
            'estado' => 'rechazado',
            'observacion' => $request->motivo
        ]);
        
        return back()->with('success', 'Pago rechazado correctamente.');
    }

    public function pagosPendientes()
    {
        $pagosPendientes = PedidoPago::with(['pedido.user', 'pedido.transporte'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->paginate(20);
        
        return view('admin.pedidos.pagos-pendientes', compact('pagosPendientes'));
    }

    public function estadisticas()
    {
        $totalPedidos = Pedido::count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $pedidosPagados = Pedido::where('estado', 'pagado')->count();
        $totalRecaudado = Pedido::where('estado', 'pagado')
            ->orWhere('estado', 'parcial')
            ->withSum('pagos', 'monto')
            ->get()
            ->sum('pagos_sum_monto');
        
        $pedidosPorEstado = Pedido::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();
        
        return view('admin.pedidos.estadisticas', compact(
            'totalPedidos',
            'pedidosPendientes',
            'pedidosPagados',
            'totalRecaudado',
            'pedidosPorEstado'
        ));
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
}
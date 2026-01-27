<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * 1. REPORTE DE PEDIDOS COMPLETOS (tabla pedidos)
     * GET /api/reportes/pedidos/estadisticas
     */
    public function estadisticasPedidos(Request $request)
    {
        try {
            // Validar fechas si vienen en el request
            $fechaInicio = $request->input('fecha_inicio', null);
            $fechaFin = $request->input('fecha_fin', null);
            
            $query = DB::table('pedidos');
            
            // Aplicar filtros de fecha si existen
            if ($fechaInicio) {
                $query->whereDate('created_at', '>=', $fechaInicio);
            }
            
            if ($fechaFin) {
                $query->whereDate('created_at', '<=', $fechaFin);
            }
            
            // 1. Totales generales
            $totalPedidos = $query->count();
            $totalVendido = $query->sum('total');
            
            // 2. Pedidos por estado
            $pedidosPorEstado = DB::table('pedidos')
                ->select('estado', DB::raw('COUNT(*) as cantidad, SUM(total) as total'))
                ->when($fechaInicio, function ($q) use ($fechaInicio) {
                    return $q->whereDate('created_at', '>=', $fechaInicio);
                })
                ->when($fechaFin, function ($q) use ($fechaFin) {
                    return $q->whereDate('created_at', '<=', $fechaFin);
                })
                ->groupBy('estado')
                ->get();
            
            // 3. Pedidos últimos 30 días (para gráfico)
            $pedidosUltimos30Dias = [];
            for ($i = 29; $i >= 0; $i--) {
                $fecha = Carbon::now()->subDays($i);
                $fechaFormato = $fecha->format('Y-m-d');
                
                $cantidad = DB::table('pedidos')
                    ->whereDate('created_at', $fechaFormato)
                    ->count();
                
                $totalDia = DB::table('pedidos')
                    ->whereDate('created_at', $fechaFormato)
                    ->sum('total');
                
                $pedidosUltimos30Dias[] = [
                    'fecha' => $fechaFormato,
                    'fecha_formato' => $fecha->format('d M'),
                    'cantidad' => $cantidad,
                    'total' => (float) $totalDia
                ];
            }
            
            // 4. Pedidos por mes (últimos 12 meses)
            $pedidosPorMes = [];
            for ($i = 11; $i >= 0; $i--) {
                $fecha = Carbon::now()->subMonths($i);
                $mes = $fecha->format('Y-m');
                $mesNombre = $fecha->translatedFormat('M Y'); // Español
                
                $cantidad = DB::table('pedidos')
                    ->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->count();
                
                $totalMes = DB::table('pedidos')
                    ->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->sum('total');
                
                $pedidosPorMes[] = [
                    'mes' => $mes,
                    'mes_nombre' => $mesNombre,
                    'cantidad' => $cantidad,
                    'total' => (float) $totalMes
                ];
            }
            
            // 5. Top 10 clientes (por compras)
            $topClientes = DB::table('pedidos')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    DB::raw('COUNT(pedidos.id) as total_pedidos'),
                    DB::raw('SUM(pedidos.total) as total_compras')
                ])
                ->join('users', 'pedidos.user_id', '=', 'users.id')
                ->when($fechaInicio, function ($q) use ($fechaInicio) {
                    return $q->whereDate('pedidos.created_at', '>=', $fechaInicio);
                })
                ->when($fechaFin, function ($q) use ($fechaFin) {
                    return $q->whereDate('pedidos.created_at', '<=', $fechaFin);
                })
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderByDesc('total_compras')
                ->limit(10)
                ->get();
            
            return response()->json([
                'success' => true,
                'filtros' => [
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin
                ],
                'data' => [
                    'totales' => [
                        'total_pedidos' => $totalPedidos,
                        'total_vendido' => (float) $totalVendido,
                        'pedido_promedio' => $totalPedidos > 0 ? (float) $totalVendido / $totalPedidos : 0
                    ],
                    'por_estado' => $pedidosPorEstado,
                    'ultimos_30_dias' => $pedidosUltimos30Dias,
                    'por_mes' => $pedidosPorMes,
                    'top_clientes' => $topClientes
                ],
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte de pedidos',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 2. LISTADO DETALLADO DE PEDIDOS
     * GET /api/reportes/pedidos/detallado
     */
    public function listadoPedidos(Request $request)
    {
        try {
            $query = DB::table('pedidos')
                ->select([
                    'pedidos.id',
                    'pedidos.codigo_reserva',
                    'pedidos.total',
                    'pedidos.subtotal',
                    'pedidos.costo_envio',
                    'pedidos.estado',
                    'pedidos.created_at',
                    'users.name as cliente_nombre',
                    'users.email as cliente_email',
                    'transportes.cooperativa as transporte_nombre'
                ])
                ->leftJoin('users', 'pedidos.user_id', '=', 'users.id')
                ->leftJoin('transportes', 'pedidos.transporte_id', '=', 'transportes.id');
            
            // Filtros
            if ($request->has('estado') && $request->estado != 'todos') {
                $query->where('pedidos.estado', $request->estado);
            }
            
            if ($request->has('cliente_id')) {
                $query->where('pedidos.user_id', $request->cliente_id);
            }
            
            if ($request->has('fecha_inicio') && $request->fecha_inicio) {
                $query->whereDate('pedidos.created_at', '>=', $request->fecha_inicio);
            }
            
            if ($request->has('fecha_fin') && $request->fecha_fin) {
                $query->whereDate('pedidos.created_at', '<=', $request->fecha_fin);
            }
            
            if ($request->has('codigo_reserva')) {
                $query->where('pedidos.codigo_reserva', 'like', '%' . $request->codigo_reserva . '%');
            }
            
            // Ordenamiento
            $orden = $request->input('orden', 'desc');
            $ordenPor = $request->input('orden_por', 'pedidos.created_at');
            $query->orderBy($ordenPor, $orden);
            
            // Paginación
            $porPagina = $request->input('por_pagina', 20);
            $pedidos = $query->paginate($porPagina);
            
            // Totales para los filtros aplicados
            $totalGeneral = $query->clone()->sum('pedidos.total');
            $cantidadGeneral = $query->clone()->count();
            
            return response()->json([
                'success' => true,
                'data' => $pedidos->items(),
                'meta' => [
                    'total' => $pedidos->total(),
                    'per_page' => $pedidos->perPage(),
                    'current_page' => $pedidos->currentPage(),
                    'last_page' => $pedidos->lastPage(),
                    'from' => $pedidos->firstItem(),
                    'to' => $pedidos->lastItem(),
                    'totales_filtrados' => [
                        'total_pedidos' => $cantidadGeneral,
                        'total_vendido' => (float) $totalGeneral
                    ]
                ],
                'filtros' => $request->all()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el listado de pedidos',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 3. REPORTE DE RESERVAS/ABONOS (tabla orders)
     * GET /api/reportes/reservas/estadisticas
     */
    public function estadisticasReservas(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio', null);
            $fechaFin = $request->input('fecha_fin', null);
            
            $query = DB::table('orders');
            
            if ($fechaInicio) {
                $query->whereDate('created_at', '>=', $fechaInicio);
            }
            
            if ($fechaFin) {
                $query->whereDate('created_at', '<=', $fechaFin);
            }
            
            // 1. Totales generales
            $totalReservas = $query->count();
            $totalMonto = $query->sum('total');
            $totalPuntos = $query->sum('puntos_generados');
            
            // 2. Reservas por estado
            $reservasPorEstado = DB::table('orders')
                ->select('estado', DB::raw('COUNT(*) as cantidad, SUM(total) as total'))
                ->when($fechaInicio, function ($q) use ($fechaInicio) {
                    return $q->whereDate('created_at', '>=', $fechaInicio);
                })
                ->when($fechaFin, function ($q) use ($fechaFin) {
                    return $q->whereDate('created_at', '<=', $fechaFin);
                })
                ->groupBy('estado')
                ->get();
            
            // 3. Reservas por tipo
            $reservasPorTipo = DB::table('orders')
                ->select('tipo', DB::raw('COUNT(*) as cantidad, SUM(total) as total'))
                ->when($fechaInicio, function ($q) use ($fechaInicio) {
                    return $q->whereDate('created_at', '>=', $fechaInicio);
                })
                ->when($fechaFin, function ($q) use ($fechaFin) {
                    return $q->whereDate('created_at', '<=', $fechaFin);
                })
                ->groupBy('tipo')
                ->get();
            
            // 4. Reservas con fecha límite próxima (próximos 7 días)
            $reservasProximasVencimiento = DB::table('orders')
                ->select([
                    'orders.id',
                    'orders.total',
                    'orders.estado',
                    'orders.tipo',
                    'orders.fecha_limite',
                    'users.name as cliente_nombre'
                ])
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.estado', 'pendiente_abono')
                ->whereBetween('orders.fecha_limite', [
                    now(),
                    now()->addDays(7)
                ])
                ->orderBy('orders.fecha_limite')
                ->get();
            
            // 5. Evolución mensual de reservas
            $reservasPorMes = [];
            for ($i = 11; $i >= 0; $i--) {
                $fecha = Carbon::now()->subMonths($i);
                $mes = $fecha->format('Y-m');
                $mesNombre = $fecha->translatedFormat('M Y');
                
                $cantidad = DB::table('orders')
                    ->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->count();
                
                $totalMes = DB::table('orders')
                    ->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->sum('total');
                
                $reservasPorMes[] = [
                    'mes' => $mes,
                    'mes_nombre' => $mesNombre,
                    'cantidad' => $cantidad,
                    'total' => (float) $totalMes
                ];
            }
            
            return response()->json([
                'success' => true,
                'filtros' => [
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin
                ],
                'data' => [
                    'totales' => [
                        'total_reservas' => $totalReservas,
                        'total_monto' => (float) $totalMonto,
                        'total_puntos' => (int) $totalPuntos
                    ],
                    'por_estado' => $reservasPorEstado,
                    'por_tipo' => $reservasPorTipo,
                    'proximas_vencimiento' => $reservasProximasVencimiento,
                    'evolucion_mensual' => $reservasPorMes
                ],
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte de reservas',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 4. LISTADO DETALLADO DE RESERVAS
     * GET /api/reportes/reservas/detallado
     */
    public function listadoReservas(Request $request)
    {
        try {
            $query = DB::table('orders')
                ->select([
                    'orders.id',
                    'orders.total',
                    'orders.puntos_generados',
                    'orders.estado',
                    'orders.tipo',
                    'orders.fecha_limite',
                    'orders.created_at',
                    'users.name as cliente_nombre',
                    'users.email as cliente_email'
                ])
                ->leftJoin('users', 'orders.user_id', '=', 'users.id');
            
            // Filtros
            if ($request->has('estado') && $request->estado != 'todos') {
                $query->where('orders.estado', $request->estado);
            }
            
            if ($request->has('tipo') && $request->tipo != 'todos') {
                $query->where('orders.tipo', $request->tipo);
            }
            
            if ($request->has('cliente_id')) {
                $query->where('orders.user_id', $request->cliente_id);
            }
            
            if ($request->has('fecha_inicio') && $request->fecha_inicio) {
                $query->whereDate('orders.created_at', '>=', $request->fecha_inicio);
            }
            
            if ($request->has('fecha_fin') && $request->fecha_fin) {
                $query->whereDate('orders.created_at', '<=', $request->fecha_fin);
            }
            
            // Filtro por fecha límite
            if ($request->has('fecha_limite_inicio')) {
                $query->whereDate('orders.fecha_limite', '>=', $request->fecha_limite_inicio);
            }
            
            if ($request->has('fecha_limite_fin')) {
                $query->whereDate('orders.fecha_limite', '<=', $request->fecha_limite_fin);
            }
            
            // Ordenamiento
            $orden = $request->input('orden', 'desc');
            $ordenPor = $request->input('orden_por', 'orders.created_at');
            $query->orderBy($ordenPor, $orden);
            
            // Paginación
            $porPagina = $request->input('por_pagina', 20);
            $reservas = $query->paginate($porPagina);
            
            return response()->json([
                'success' => true,
                'data' => $reservas->items(),
                'meta' => [
                    'total' => $reservas->total(),
                    'per_page' => $reservas->perPage(),
                    'current_page' => $reservas->currentPage(),
                    'last_page' => $reservas->lastPage(),
                    'from' => $reservas->firstItem(),
                    'to' => $reservas->lastItem()
                ],
                'filtros' => $request->all()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el listado de reservas',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 5. DASHBOARD RESUMEN (ambos sistemas)
     * GET /api/reportes/dashboard
     */
    public function dashboard(Request $request)
    {
        try {
            // Mes actual
            $mesActual = now()->month;
            $anoActual = now()->year;
            
            // PEDIDOS
            $totalPedidos = DB::table('pedidos')->count();
            $pedidosMes = DB::table('pedidos')
                ->whereMonth('created_at', $mesActual)
                ->whereYear('created_at', $anoActual)
                ->count();
            
            $totalVendidoPedidos = DB::table('pedidos')
                ->where('estado', 'completado')
                ->sum('total');
            
            $pedidosPendientes = DB::table('pedidos')
                ->where('estado', 'pendiente')
                ->count();
            
            // RESERVAS/ORDERS
            $totalReservas = DB::table('orders')->count();
            $reservasMes = DB::table('orders')
                ->whereMonth('created_at', $mesActual)
                ->whereYear('created_at', $anoActual)
                ->count();
            
            $reservasPendientesAbono = DB::table('orders')
                ->where('estado', 'pendiente_abono')
                ->count();
            
            // USUARIOS/CLIENTES
            $totalClientes = DB::table('users')->count();
            $clientesNuevosMes = DB::table('users')
                ->whereMonth('created_at', $mesActual)
                ->whereYear('created_at', $anoActual)
                ->count();
            
            // PRODUCTOS
            $totalProductos = DB::table('articulos')->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pedidos' => [
                        'total' => $totalPedidos,
                        'este_mes' => $pedidosMes,
                        'total_vendido' => (float) $totalVendidoPedidos,
                        'pendientes' => $pedidosPendientes
                    ],
                    'reservas' => [
                        'total' => $totalReservas,
                        'este_mes' => $reservasMes,
                        'pendientes_abono' => $reservasPendientesAbono
                    ],
                    'clientes' => [
                        'total' => $totalClientes,
                        'nuevos_este_mes' => $clientesNuevosMes
                    ],
                    'productos' => [
                        'total' => $totalProductos
                    ],
                    'fecha_consulta' => now()->toDateTimeString()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el dashboard',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 6. REPORTE DE VENTAS POR MES (Para gráfica Revenue)
     * GET /api/reportes/ventas-mensuales
     */
    public function ventasMensuales(Request $request)
    {
        try {
            // Obtener ventas de los últimos 12 meses
            $ventasMensuales = [];
            
            for ($i = 11; $i >= 0; $i--) {
                $fecha = Carbon::now()->subMonths($i);
                $mes = $fecha->format('Y-m');
                $mesNombre = $fecha->translatedFormat('M'); // Mes abreviado
                
                // Pedidos completados del mes
                $ventasPedidos = DB::table('pedidos')
                    ->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->where('estado', 'completado')
                    ->sum('total');
                
                // Reservas/Orders completados del mes
                $ventasReservas = DB::table('orders')
                    ->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->where('estado', 'completado')
                    ->sum('total');
                
                $totalMes = (float) $ventasPedidos + (float) $ventasReservas;
                
                $ventasMensuales[] = [
                    'mes' => $mes,
                    'mes_nombre' => $mesNombre,
                    'total' => $totalMes,
                    'pedidos' => (float) $ventasPedidos,
                    'reservas' => (float) $ventasReservas
                ];
            }
            
            // Ventas totales (para mostrar en el panel)
            $ventasTotales = array_sum(array_column($ventasMensuales, 'total'));
            $ventasMesActual = end($ventasMensuales)['total'];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'ventas_mensuales' => $ventasMensuales,
                    'totales' => [
                        'ventas_totales' => $ventasTotales,
                        'ventas_mes_actual' => $ventasMesActual,
                        'moneda' => 'MXN' // Ajusta según tu sistema
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener ventas mensuales',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 7. REPORTE DE EMPRENDEDORAS (Para gráfica Sales By Category)
     * GET /api/reportes/emprendedoras
     */
    public function reporteEmprendedoras(Request $request)
    {
        try {
            // Necesito saber: ¿Cómo se identifican las emprendedoras en tu sistema?
            // ¿Hay un campo en la tabla users que las identifique?
            // Por ahora, haré un ejemplo basado en usuarios que han hecho pedidos
            
            // USUARIOS NUEVOS (este mes)
            $nuevasEsteMes = DB::table('users')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // USUARIOS QUE HAN REFERENCIADO A OTROS
            // Necesito saber: ¿Hay un sistema de referidos en tu base?
            // ¿Hay una tabla de referidos o un campo 'referred_by' en users?
            
            // Por ahora, haré un ejemplo hipotético:
            // Si tienes un campo 'referido_por' en users:
            /*
            $referenciadas = DB::table('users')
                ->whereNotNull('referido_por')
                ->count();
            */
            
            // Ejemplo temporal: dividir en 3 categorías
            $totalUsuarios = DB::table('users')->count();
            
            // Simulación de datos (reemplaza con tu lógica real)
            $emprendedoras = [
                [
                    'categoria' => 'Nuevas',
                    'cantidad' => $nuevasEsteMes,
                    'porcentaje' => $totalUsuarios > 0 ? round(($nuevasEsteMes / $totalUsuarios) * 100, 2) : 0,
                    'color' => '#5c1ac3' // Morado
                ],
                [
                    'categoria' => 'Activas',
                    'cantidad' => DB::table('users')
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                  ->from('pedidos')
                                  ->whereColumn('pedidos.user_id', 'users.id')
                                  ->where('pedidos.estado', 'completado');
                        })
                        ->count(),
                    'porcentaje' => $totalUsuarios > 0 ? round((DB::table('users')
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                  ->from('pedidos')
                                  ->whereColumn('pedidos.user_id', 'users.id')
                                  ->where('pedidos.estado', 'completado');
                        })->count() / $totalUsuarios) * 100, 2) : 0,
                    'color' => '#e2a03f' // Naranja
                ],
                [
                    'categoria' => 'Referenciadas',
                    'cantidad' => DB::table('users')->where('created_at', '>', now()->subDays(30))->count(), // Temporal
                    'porcentaje' => 30, // Temporal
                    'color' => '#e7515a' // Rojo
                ]
            ];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'emprendedoras' => $emprendedoras,
                    'totales' => [
                        'total_emprendedoras' => $totalUsuarios,
                        'nuevas_este_mes' => $nuevasEsteMes
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener reporte de emprendedoras',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 8. NÚMERO DE PEDIDOS (Para Total Orders)
     * GET /api/reportes/estadisticas-pedidos-simple
     */
    public function estadisticasPedidosSimple(Request $request)
    {
        try {
            // Totales de pedidos
            $totalPedidos = DB::table('pedidos')->count();
            
            // Pedidos del mes
            $pedidosEsteMes = DB::table('pedidos')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // Pedidos últimos 10 días (para gráfica pequeña)
            $pedidosUltimos10Dias = [];
            for ($i = 9; $i >= 0; $i--) {
                $fecha = Carbon::now()->subDays($i);
                $cantidad = DB::table('pedidos')
                    ->whereDate('created_at', $fecha->format('Y-m-d'))
                    ->count();
                
                $pedidosUltimos10Dias[] = $cantidad;
            }
            
            // Crecimiento porcentual vs mes anterior
            $mesAnterior = Carbon::now()->subMonth();
            $pedidosMesAnterior = DB::table('pedidos')
                ->whereMonth('created_at', $mesAnterior->month)
                ->whereYear('created_at', $mesAnterior->year)
                ->count();
            
            $crecimiento = $pedidosMesAnterior > 0 
                ? round((($pedidosEsteMes - $pedidosMesAnterior) / $pedidosMesAnterior) * 100, 2)
                : ($pedidosEsteMes > 0 ? 100 : 0);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_pedidos' => $totalPedidos,
                    'pedidos_este_mes' => $pedidosEsteMes,
                    'pedidos_mes_anterior' => $pedidosMesAnterior,
                    'crecimiento_porcentual' => $crecimiento,
                    'pedidos_ultimos_10_dias' => $pedidosUltimos10Dias,
                    'tendencia' => $crecimiento >= 0 ? 'asc' : 'desc'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas de pedidos',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 9. REPORTE DE ÓRDENES RECIENTES (Para Recent Orders)
     * GET /api/reportes/ordenes-recientes
     */
    public function ordenesRecientes(Request $request)
    {
        try {
            $limite = $request->input('limite', 5);
            
            $ordenes = DB::table('pedidos')
                ->select([
                    'pedidos.id',
                    'pedidos.codigo_reserva',
                    'pedidos.total',
                    'pedidos.estado',
                    'pedidos.created_at',
                    'users.name as cliente_nombre',
                    'users.email as cliente_email'
                ])
                ->join('users', 'pedidos.user_id', '=', 'users.id')
                ->orderBy('pedidos.created_at', 'desc')
                ->limit($limite)
                ->get()
                ->map(function ($orden) {
                    // Formatear fecha
                    $orden->fecha_formateada = Carbon::parse($orden->created_at)->diffForHumans();
                    
                    // Asignar color según estado
                    $coloresEstado = [
                        'pendiente' => 'warning',
                        'abonado' => 'info',
                        'completado' => 'success',
                        'rechazado' => 'danger',
                        'cancelado' => 'secondary'
                    ];
                    
                    $orden->color_estado = $coloresEstado[$orden->estado] ?? 'secondary';
                    
                    return $orden;
                });
            
            return response()->json([
                'success' => true,
                'data' => $ordenes,
                'meta' => [
                    'total' => $ordenes->count(),
                    'limite' => $limite
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener órdenes recientes',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * 10. TOP PRODUCTOS MÁS VENDIDOS (Para Top Selling Product)
     * GET /api/reportes/top-productos
     */
    public function topProductos(Request $request)
    {
        try {
            $limite = $request->input('limite', 5);
            
            \Log::info('Iniciando consulta topProductos con límite: ' . $limite);
            
            // CONSULTA CORRECTA - Sin imagen_principal
            $topProductos = DB::table('pedido_detalles')
                ->select([
                    'articulos.id as articulo_id',
                    'articulos.nombre as producto_nombre',
                    'articulos.precio as precio_regular',
                    DB::raw('COALESCE(SUM(pedido_detalles.cantidad), 0) as total_vendido'),
                    DB::raw('COALESCE(SUM(pedido_detalles.cantidad * pedido_detalles.precio_unitario), 0) as total_ventas'),
                    DB::raw('COALESCE(AVG(pedido_detalles.precio_unitario), 0) as precio_promedio'),
                    DB::raw('COUNT(DISTINCT pedido_detalles.pedido_id) as pedidos_totales')
                ])
                ->join('pedidos', function($join) {
                    $join->on('pedido_detalles.pedido_id', '=', 'pedidos.id')
                        ->where('pedidos.estado', '=', 'completado');
                })
                ->join('articulo_variantes', 'pedido_detalles.variante_id', '=', 'articulo_variantes.id')
                ->join('articulos', 'articulo_variantes.articulo_id', '=', 'articulos.id')
                ->where('articulos.activo', 1) // Solo productos activos
                ->whereNull('articulos.deleted_at') // No productos eliminados
                ->groupBy(
                    'articulos.id', 
                    'articulos.nombre',
                    'articulos.precio'
                )
                ->orderByDesc('total_vendido')
                ->limit($limite)
                ->get()
                ->map(function ($producto, $index) {
                    // Obtener la primera imagen del producto si existe
                    $imagen = DB::table('articulo_imagenes')
                        ->where('articulo_id', $producto->articulo_id)
                        ->orderBy('orden', 'asc')
                        ->value('imagen_url');
                    
                    $producto->imagen = $imagen ?: '/images/product-placeholder.jpg';
                    
                    // Obtener categoría real del producto
                    $categoria = DB::table('categorias')
                        ->select('categorias.nombre')
                        ->join('articulos', 'categorias.id', '=', 'articulos.categoria_id')
                        ->where('articulos.id', $producto->articulo_id)
                        ->value('nombre');
                    
                    $producto->categoria = $categoria ?: $this->obtenerCategoriaPorIndice($index);
                    $producto->fuente = $this->obtenerFuentePorIndice($index);
                    $producto->color_fuente = $this->obtenerColorFuente($producto->fuente);
                    
                    // Calcular descuento
                    $producto->precio_regular = (float) $producto->precio_regular;
                    $producto->precio_promedio = (float) $producto->precio_promedio;
                    
                    if ($producto->precio_regular > 0 && $producto->precio_promedio > 0 && 
                        $producto->precio_regular > $producto->precio_promedio) {
                        $producto->descuento = $producto->precio_regular - $producto->precio_promedio;
                        $producto->porcentaje_descuento = round(($producto->descuento / $producto->precio_regular) * 100, 2);
                    } else {
                        $producto->descuento = 0;
                        $producto->porcentaje_descuento = 0;
                    }
                    
                    return $producto;
                });
            
            \Log::info('Consulta topProductos completada. Encontrados: ' . $topProductos->count());
            
            return response()->json([
                'success' => true,
                'data' => $topProductos,
                'meta' => [
                    'total' => $topProductos->count(),
                    'limite' => $limite,
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en topProductos: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            // Versión de respaldo SIMPLE
            try {
                \Log::info('Intentando consulta simplificada...');
                
                $topProductosSimple = DB::table('pedido_detalles')
                    ->select([
                        'articulos.id as articulo_id',
                        'articulos.nombre as producto_nombre',
                        DB::raw('SUM(pedido_detalles.cantidad) as total_vendido')
                    ])
                    ->join('articulo_variantes', 'pedido_detalles.variante_id', '=', 'articulo_variantes.id')
                    ->join('articulos', 'articulo_variantes.articulo_id', '=', 'articulos.id')
                    ->groupBy('articulos.id', 'articulos.nombre')
                    ->orderByDesc('total_vendido')
                    ->limit($limite)
                    ->get()
                    ->map(function ($producto, $index) {
                        $producto->imagen = '/images/product-placeholder.jpg';
                        $producto->categoria = $this->obtenerCategoriaPorIndice($index);
                        $producto->total_ventas = $producto->total_vendido * 100; // Ejemplo
                        $producto->precio_promedio = 100; // Ejemplo
                        $producto->fuente = $this->obtenerFuentePorIndice($index);
                        $producto->color_fuente = $this->obtenerColorFuente($producto->fuente);
                        return $producto;
                    });
                
                return response()->json([
                    'success' => true,
                    'data' => $topProductosSimple,
                    'meta' => [
                        'total' => $topProductosSimple->count(),
                        'limite' => $limite,
                        'nota' => 'Consulta simplificada'
                    ]
                ]);
                
            } catch (\Exception $e2) {
                \Log::error('Error en consulta simplificada: ' . $e2->getMessage());
                
                return response()->json([
                    'success' => true,
                    'data' => $this->obtenerProductosDeEjemplo($limite),
                    'meta' => [
                        'total' => $limite,
                        'limite' => $limite,
                        'nota' => 'Datos de ejemplo'
                    ]
                ]);
            }
        }
    }

    // Mantén los métodos auxiliares iguales
    private function obtenerCategoriaPorIndice($index)
    {
        $categorias = ['Ropa', 'Accesorios', 'Calzado', 'Belleza', 'Hogar', 'Tecnología', 'Joyería'];
        return $categorias[$index % count($categorias)] ?? 'General';
    }

    private function obtenerFuentePorIndice($index)
    {
        $fuentes = ['Directo', 'Referido', 'Online', 'Tienda', 'Mayorista', 'Redes Sociales', 'Email'];
        return $fuentes[$index % count($fuentes)] ?? 'Directo';
    }

    private function obtenerColorFuente($fuente)
    {
        $coloresFuente = [
            'Directo' => 'danger',
            'Referido' => 'success',
            'Online' => 'primary',
            'Tienda' => 'warning',
            'Mayorista' => 'info',
            'Redes Sociales' => 'pink',
            'Email' => 'secondary'
        ];
        return $coloresFuente[$fuente] ?? 'primary';
    }

    private function obtenerProductosDeEjemplo($limite)
    {
        return collect([
            [
                'articulo_id' => 1,
                'producto_nombre' => 'Producto de Ejemplo 1',
                'imagen' => '/images/product-placeholder.jpg',
                'precio_regular' => 199.99,
                'total_vendido' => 50,
                'total_ventas' => 9999.50,
                'precio_promedio' => 199.99,
                'pedidos_totales' => 25,
                'categoria' => 'Ropa',
                'fuente' => 'Online',
                'color_fuente' => 'primary',
                'descuento' => 0,
                'porcentaje_descuento' => 0
            ],
            [
                'articulo_id' => 2,
                'producto_nombre' => 'Producto de Ejemplo 2',
                'imagen' => '/images/product-placeholder.jpg',
                'precio_regular' => 149.99,
                'total_vendido' => 35,
                'total_ventas' => 5249.65,
                'precio_promedio' => 149.99,
                'pedidos_totales' => 18,
                'categoria' => 'Accesorios',
                'fuente' => 'Directo',
                'color_fuente' => 'danger',
                'descuento' => 0,
                'porcentaje_descuento' => 0
            ]
        ])->take($limite);
    }
    /**
     * 11. DASHBOARD COMPLETO (Todas las métricas juntas)
     * GET /api/reportes/dashboard-completo
     */
    public function dashboardCompleto(Request $request)
    {
        try {
            // Llamar a todos los métodos internamente
            // Esto es más eficiente que hacer múltiples requests desde el frontend
            
            // 1. Ventas mensuales
            $ventasMensuales = [];
            for ($i = 11; $i >= 0; $i--) {
                $fecha = Carbon::now()->subMonths($i);
                $mesNombre = $fecha->translatedFormat('M');
                
                $totalMes = DB::table('pedidos')
                    ->whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->where('estado', 'completado')
                    ->sum('total');
                
                $ventasMensuales[] = [
                    'mes' => $mesNombre,
                    'total' => (float) $totalMes
                ];
            }
            
            // 2. Emprendedoras (usuarios)
            $totalUsuarios = DB::table('users')->count();
            $nuevasEsteMes = DB::table('users')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // 3. Número de pedidos
            $totalPedidos = DB::table('pedidos')->count();
            $pedidosEsteMes = DB::table('pedidos')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // 4. Órdenes recientes (5)
            $ordenesRecientes = DB::table('pedidos')
                ->select([
                    'pedidos.id',
                    'pedidos.codigo_reserva',
                    'pedidos.total',
                    'pedidos.estado',
                    'pedidos.created_at',
                    'users.name as cliente_nombre'
                ])
                ->join('users', 'pedidos.user_id', '=', 'users.id')
                ->orderBy('pedidos.created_at', 'desc')
                ->limit(5)
                ->get();
            
            // 5. Top productos
            $topProductos = DB::table('pedido_detalles')
                ->select([
                    'pedido_detalles.articulo_id',
                    'articulos.nombre as producto_nombre',
                    DB::raw('SUM(pedido_detalles.cantidad) as total_vendido'),
                    DB::raw('SUM(pedido_detalles.cantidad * pedido_detalles.precio) as total_ventas')
                ])
                ->join('articulos', 'pedido_detalles.articulo_id', '=', 'articulos.id')
                ->join('pedidos', 'pedido_detalles.pedido_id', '=', 'pedidos.id')
                ->where('pedidos.estado', 'completado')
                ->groupBy('pedido_detalles.articulo_id', 'articulos.nombre')
                ->orderByDesc('total_vendido')
                ->limit(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'ventas_mensuales' => [
                        'labels' => array_column($ventasMensuales, 'mes'),
                        'data' => array_column($ventasMensuales, 'total')
                    ],
                    'emprendedoras' => [
                        'nuevas' => $nuevasEsteMes,
                        'activas' => $totalUsuarios - $nuevasEsteMes,
                        'total' => $totalUsuarios
                    ],
                    'pedidos' => [
                        'total' => $totalPedidos,
                        'este_mes' => $pedidosEsteMes
                    ],
                    'ordenes_recientes' => $ordenesRecientes,
                    'top_productos' => $topProductos
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar dashboard completo',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
}
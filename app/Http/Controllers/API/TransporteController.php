<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transporte;
use App\Http\Requests\StoreTransporteRequest;
use App\Http\Requests\UpdateTransporteRequest;
use App\Http\Resources\TransporteResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransporteController extends Controller
{
    public function index(Request $request)
    {
        try {
            \Log::info('🚀 INICIO TransporteController@index');
            \Log::info('📋 Parámetros recibidos:', $request->all());
            
            $query = Transporte::query();
            
            // DEPURACIÓN: Verificar cada filtro individualmente
            \Log::info('🔍 Aplicando filtros...');
            
            // Filtro por búsqueda - CORREGIDO
            if ($request->filled('search')) {
                \Log::info("🔍 Aplicando filtro search: {$request->search}");
                $query->where(function($q) use ($request) {
                    $q->where('ruta', 'like', '%' . $request->search . '%')
                    ->orWhere('cooperativa', 'like', '%' . $request->search . '%');
                });
            } else {
                \Log::info("🔍 NO se aplica filtro search (vacío)");
            }
            
            // Filtro por cooperativa - CORREGIDO  
            if ($request->filled('cooperativa')) {
                \Log::info("🔍 Aplicando filtro cooperativa: {$request->cooperativa}");
                $query->where('cooperativa', $request->cooperativa);
            } else {
                \Log::info("🔍 NO se aplica filtro cooperativa (vacío)");
            }
            
            // Filtro por estado - ¡ESTO ES EL PROBLEMA PRINCIPAL!
            // $request->estado viene como string, pero en BD es boolean/tinyint
            if ($request->filled('estado')) {
                \Log::info("🔍 Aplicando filtro estado: {$request->estado}");
                
                // Convertir string a boolean/integer
                $estadoValue = filter_var($request->estado, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                
                if ($estadoValue !== null) {
                    $query->where('estado', $estadoValue);
                    \Log::info("🔍 Estado convertido a: " . ($estadoValue ? 'true/1' : 'false/0'));
                } else {
                    \Log::warning("⚠️ Valor de estado inválido: {$request->estado}");
                }
            } else {
                \Log::info("🔍 NO se aplica filtro estado (vacío)");
            }
            
            // DEPURACIÓN: Verificar query después de filtros
            \Log::info("🔍 SQL después de filtros: " . $query->toSql());
            \Log::info("🔍 Bindings después de filtros: ", $query->getBindings());
            
            // Contar después de filtros (pero antes de ordenar)
            $countAfterFilters = $query->count();
            \Log::info("🔍 Conteo después de filtros: {$countAfterFilters}");
            
            // Si después de filtros es 0 pero debería haber datos, algo está mal
            if ($countAfterFilters === 0 && !$request->filled('search') && !$request->filled('cooperativa') && !$request->filled('estado')) {
                \Log::warning("⚠️ ¡ALERTA! Consulta con filtros vacíos devuelve 0 registros");
                \Log::warning("⚠️ SQL problemático: " . $query->toSql());
                
                // Resetear query y probar sin ningún filtro
                $query = Transporte::query();
                \Log::info("🔄 Query reseteada. Nuevo conteo: " . $query->count());
            }
            
            // Ordenar - PROBLEMA CON created_at NULL
            $orderBy = $request->get('order_by', 'id'); // Cambiado de 'created_at' a 'id'
            $orderDir = $request->get('order_dir', 'asc');
            
            \Log::info("🔍 Ordenando por: {$orderBy} {$orderDir}");
            
            // Si ordenamos por created_at y todos son NULL, cambiar a id
            if ($orderBy === 'created_at') {
                // Verificar si hay algún created_at no nulo
                $hasNonNullCreatedAt = Transporte::whereNotNull('created_at')->exists();
                
                if (!$hasNonNullCreatedAt) {
                    \Log::warning("⚠️ Todos los created_at son NULL, ordenando por id en lugar de created_at");
                    $orderBy = 'id';
                }
            }
            
            $query->orderBy($orderBy, $orderDir);
            
            // Paginación
            $perPage = $request->get('per_page', 15);
            \Log::info("📄 Paginando con per_page: {$perPage}");
            
            $transportes = $query->paginate($perPage);
            
            \Log::info("📊 Resultados paginados: " . $transportes->count() . " items");
            \Log::info("📊 Total de registros: " . $transportes->total());
            
            // Si paginación devuelve 0 pero debería haber datos, usar simple get
            if ($transportes->count() === 0 && $transportes->total() === 0) {
                \Log::warning("⚠️ Paginación devuelve 0, probando con get() simple");
                $allResults = $query->get();
                \Log::info("🔍 Resultados con get() simple: " . $allResults->count());
                
                if ($allResults->count() > 0) {
                    \Log::warning("⚠️ ¡Problema con paginación detectado!");
                    
                    // Devolver resultados sin paginación como fallback
                    $data = $allResults->map(function ($transporte) {
                        return [
                            'id' => $transporte->id,
                            'ruta' => $transporte->ruta,
                            'precio' => (float) $transporte->precio,
                            'precio_formateado' => '$' . number_format($transporte->precio, 2, '.', ','),
                            'cooperativa' => $transporte->cooperativa,
                            'estado' => (bool) $transporte->estado,
                            'estado_label' => $transporte->estado ? 'Activo' : 'Inactivo',
                            'tiempo_estimado' => $transporte->tiempo_estimado,
                            'tiempo_estimado_formateado' => $transporte->tiempo_estimado 
                                ? $this->formatTime($transporte->tiempo_estimado)
                                : null,
                            'created_at' => $transporte->created_at 
                                ? $transporte->created_at->format('Y-m-d H:i:s')
                                : null,
                            'updated_at' => $transporte->updated_at 
                                ? $transporte->updated_at->format('Y-m-d H:i:s')
                                : null,
                        ];
                    });
                    
                    \Log::info('✅ Devolviendo datos con fallback (sin paginación)');
                    
                    return response()->json([
                        'success' => true,
                        'data' => $data,
                        'meta' => [
                            'current_page' => 1,
                            'from' => 1,
                            'to' => $data->count(),
                            'total' => $data->count(),
                            'per_page' => $perPage,
                            'last_page' => 1,
                        ],
                        'links' => [
                            'first' => null,
                            'last' => null,
                            'prev' => null,
                            'next' => null,
                        ],
                        'debug' => [
                            'pagination_issue' => true,
                            'message' => 'Usando fallback sin paginación debido a problema técnico'
                        ]
                    ]);
                }
            }
            
            // Formatear datos normalmente
            $transportes->getCollection()->transform(function ($transporte) {
                return [
                    'id' => $transporte->id,
                    'ruta' => $transporte->ruta,
                    'precio' => (float) $transporte->precio,
                    'precio_formateado' => '$' . number_format($transporte->precio, 2, '.', ','),
                    'cooperativa' => $transporte->cooperativa,
                    'estado' => (bool) $transporte->estado,
                    'estado_label' => $transporte->estado ? 'Activo' : 'Inactivo',
                    'tiempo_estimado' => $transporte->tiempo_estimado,
                    'tiempo_estimado_formateado' => $transporte->tiempo_estimado 
                        ? $this->formatTime($transporte->tiempo_estimado)
                        : null,
                    'created_at' => $transporte->created_at 
                        ? $transporte->created_at->format('Y-m-d H:i:s')
                        : null,
                    'updated_at' => $transporte->updated_at 
                        ? $transporte->updated_at->format('Y-m-d H:i:s')
                        : null,
                ];
            });
            
            \Log::info('✅ TransporteController@index completado exitosamente');
            
            return response()->json([
                'success' => true,
                'data' => $transportes->items(),
                'meta' => [
                    'current_page' => $transportes->currentPage(),
                    'from' => $transportes->firstItem(),
                    'to' => $transportes->lastItem(),
                    'total' => $transportes->total(),
                    'per_page' => $transportes->perPage(),
                    'last_page' => $transportes->lastPage(),
                ],
                'links' => [
                    'first' => $transportes->url(1),
                    'last' => $transportes->url($transportes->lastPage()),
                    'prev' => $transportes->previousPageUrl(),
                    'next' => $transportes->nextPageUrl(),
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('❌ ERROR CRÍTICO en TransporteController@index: ' . $e->getMessage());
            \Log::error('❌ Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los transportes',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    // Método para formatear tiempo
    private function formatTime($hours)
    {
        if (!$hours || $hours == 0) return 'N/A';
        
        $h = floor($hours);
        $m = round(($hours - $h) * 60);
        
        if ($h > 0 && $m > 0) return "{$h}h {$m}m";
        if ($h > 0) return "{$h}h";
        return "{$m}m";
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransporteRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $transporte = Transporte::create($request->validated());
            
            DB::commit();
            
            return response()->json([
                'message' => 'Transporte creado exitosamente',
                'data' => new TransporteResource($transporte)
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Error al crear el transporte',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Transporte $transporte): JsonResponse
    {
        return response()->json([
            'data' => new TransporteResource($transporte)
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransporteRequest $request, Transporte $transporte): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $transporte->update($request->validated());
            
            DB::commit();
            
            return response()->json([
                'message' => 'Transporte actualizado exitosamente',
                'data' => new TransporteResource($transporte)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Error al actualizar el transporte',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transporte $transporte): JsonResponse
    {
        try {
            $transporte->delete();
            
            return response()->json([
                'message' => 'Transporte eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el transporte',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Restaurar transporte eliminado
     */
    public function restore($id): JsonResponse
    {
        try {
            $transporte = Transporte::withTrashed()->findOrFail($id);
            $transporte->restore();
            
            return response()->json([
                'message' => 'Transporte restaurado exitosamente',
                'data' => new TransporteResource($transporte)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al restaurar el transporte',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Listar rutas disponibles
     */
    public function rutasDisponibles(): JsonResponse
    {
        $rutas = Transporte::activos()
            ->select('id', 'ruta', 'precio', 'cooperativa', 'tiempo_estimado')
            ->get()
            ->map(function ($transporte) {
                return [
                    'value' => $transporte->id,
                    'label' => "{$transporte->ruta} - {$transporte->cooperativa} (${$transporte->precio})",
                    'precio' => $transporte->precio,
                    'cooperativa' => $transporte->cooperativa,
                    'tiempo_estimado' => $transporte->tiempo_estimado_formateado
                ];
            });
        
        return response()->json([
            'data' => $rutas
        ]);
    }
    
    /**
     * Listar cooperativas únicas
     */
    public function cooperativas()
    {
        try {
            $cooperativas = Transporte::distinct('cooperativa')
                ->whereNotNull('cooperativa')
                ->orderBy('cooperativa')
                ->pluck('cooperativa');
            
            return response()->json([
                'success' => true,
                'data' => $cooperativas
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en TransporteController@cooperativas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar cooperativas'
            ], 500);
        }
    }
    
    /**
     * Alternar estado
     */
    public function toggleEstado(Transporte $transporte): JsonResponse
    {
        try {
            $transporte->update(['estado' => !$transporte->estado]);
            
            return response()->json([
                'message' => 'Estado actualizado exitosamente',
                'data' => new TransporteResource($transporte)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar estado',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Obtener estadísticas
     */
    public function estadisticas()
    {
        try {
            $total = Transporte::count();
            $activos = Transporte::where('estado', 1)->count();
            $inactivos = Transporte::where('estado', 0)->count();
            
            $precioPromedio = Transporte::avg('precio');
            $tiempoPromedio = Transporte::avg('tiempo_estimado');
            $cooperativasUnicas = Transporte::distinct('cooperativa')->count('cooperativa');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'activos' => $activos,
                    'inactivos' => $inactivos,
                    'precio_promedio' => (float) $precioPromedio,
                    'tiempo_promedio' => (float) $tiempoPromedio,
                    'cooperativas_unicas' => $cooperativasUnicas,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en TransporteController@estadisticas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar estadísticas'
            ], 500);
        }
    }
}
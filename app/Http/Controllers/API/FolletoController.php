<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFolletoRequest;
use App\Http\Requests\UpdateFolletoRequest;
use App\Http\Resources\FolletoResource;
use App\Models\Folleto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FolletoController extends Controller
{
    /**
     * Listar folletos con filtros
     */
    public function index(Request $request)
    {
        try {
            $query = Folleto::query();
            
            // Filtro por estado
            if ($request->has('estado')) {
                $estado = filter_var($request->estado, FILTER_VALIDATE_BOOLEAN);
                $query->where('estado', $estado);
            }
            
            // Búsqueda
            if ($request->has('search') && !empty($request->search)) {
                $query->buscar($request->search);
            }
            
            // Ordenamiento
            $orderBy = $request->get('order_by', 'created_at');
            $orderDir = $request->get('order_dir', 'desc');
            
            $allowedOrders = ['nombre', 'descargas', 'created_at', 'updated_at'];
            if (in_array($orderBy, $allowedOrders)) {
                $query->orderBy($orderBy, $orderDir);
            }
            
            // Paginación
            $perPage = $request->get('per_page', 15);
            $folletos = $query->paginate($perPage);
            
            return FolletoResource::collection($folletos);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los folletos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un folleto específico
     */
    public function show(Folleto $folleto)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new FolletoResource($folleto)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el folleto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo folleto
     */
    public function store(StoreFolletoRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Manejar subida de archivo
            if ($request->hasFile('archivo_pdf')) {
                $file = $request->file('archivo_pdf');
                $fileName = time() . '_' . Str::slug($data['nombre']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('folletos', $fileName, 'public');
                $data['archivo_pdf'] = $path;
            }
            
            // Asegurar valor por defecto para descargas
            $data['descargas'] = 0;
            
            $folleto = Folleto::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Folleto creado exitosamente',
                'data' => new FolletoResource($folleto)
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el folleto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar folleto
     */
    public function update(UpdateFolletoRequest $request, Folleto $folleto)
    {
        try {
            $data = $request->validated();
            
            // Si se sube un nuevo archivo
            if ($request->hasFile('archivo_pdf')) {
                // Eliminar archivo anterior
                if (Storage::exists($folleto->archivo_pdf)) {
                    Storage::delete($folleto->archivo_pdf);
                }
                
                // Subir nuevo archivo
                $file = $request->file('archivo_pdf');
                $fileName = time() . '_' . Str::slug($data['nombre'] ?? $folleto->nombre) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('folletos', $fileName, 'public');
                $data['archivo_pdf'] = $path;
                
                // Si se solicita resetear descargas
                if ($request->get('reset_descargas', false)) {
                    $data['descargas'] = 0;
                }
            }
            
            $folleto->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Folleto actualizado exitosamente',
                'data' => new FolletoResource($folleto->refresh())
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el folleto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar folleto
     */
    public function destroy(Folleto $folleto)
    {
        try {
            // El archivo se eliminará automáticamente por el evento en el modelo
            $folleto->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Folleto eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el folleto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar estado del folleto
     */
    public function toggleEstado(Folleto $folleto)
    {
        try {
            $folleto->update(['estado' => !$folleto->estado]);
            
            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'data' => new FolletoResource($folleto)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Incrementar contador de descargas
     */
    public function incrementarDescargas(Folleto $folleto)
    {
        try {
            $folleto->incrementarDescargas();
            
            return response()->json([
                'success' => true,
                'message' => 'Contador de descargas incrementado',
                'descargas' => $folleto->descargas
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al incrementar descargas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar archivo PDF (público)
     */
    public function descargar($id)
    {
        \Log::info('=== INICIANDO DESCARGA ===');
        \Log::info('ID recibido: ' . $id);
        
        try {
            // Buscar el folleto
            $folleto = Folleto::find($id);
            
            if (!$folleto) {
                \Log::error('Folleto no encontrado para ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Folleto no encontrado'
                ], 404);
            }
            
            \Log::info('Folleto encontrado:');
            \Log::info('  ID: ' . $folleto->id);
            \Log::info('  Nombre: ' . $folleto->nombre);
            \Log::info('  Archivo PDF: ' . $folleto->archivo_pdf);
            \Log::info('  Archivo PDF es null?: ' . (is_null($folleto->archivo_pdf) ? 'SÍ' : 'NO'));
            
            // Si archivo_pdf es null, algo está mal
            if (is_null($folleto->archivo_pdf) || empty($folleto->archivo_pdf)) {
                \Log::error('El campo archivo_pdf está vacío o es null para el folleto ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo PDF no está registrado en la base de datos',
                    'data' => [
                        'id' => $folleto->id,
                        'nombre' => $folleto->nombre,
                        'archivo_pdf' => $folleto->archivo_pdf
                    ]
                ], 400);
            }
            
            // Incrementar descargas
            $folleto->increment('descargas');
            
            // Verificar si el archivo existe
            $archivoPath = $folleto->archivo_pdf;
            \Log::info('Verificando archivo en: ' . $archivoPath);
            
            if (!Storage::disk('public')->exists($archivoPath)) {
                \Log::error('Archivo no existe en storage: ' . $archivoPath);
                \Log::error('Ruta completa sería: ' . Storage::disk('public')->path($archivoPath));
                
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo PDF no existe en el servidor',
                    'archivo_path' => $archivoPath,
                    'ruta_completa' => Storage::disk('public')->path($archivoPath)
                ], 404);
            }
            
            // Preparar nombre para descarga
            $nombreArchivo = Str::slug($folleto->nombre) . '.pdf';
            \Log::info('Descargando como: ' . $nombreArchivo);
            
            // Descargar archivo
            return Storage::disk('public')->download($archivoPath, $nombreArchivo, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error inesperado al descargar folleto:');
            \Log::error('Mensaje: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno al descargar el archivo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de folletos
     */
    public function estadisticas()
    {
        try {
            $totalFolletos = Folleto::count();
            $totalActivos = Folleto::where('estado', true)->count();
            $totalDescargas = Folleto::sum('descargas');
            
            // Folleto más popular
            $folletoPopular = Folleto::orderBy('descargas', 'desc')->first();
            
            // Descargas por mes (últimos 6 meses)
            $descargasPorMes = Folleto::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as mes,
                SUM(descargas) as total_descargas
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_folletos' => $totalFolletos,
                    'total_activos' => $totalActivos,
                    'total_descargas' => $totalDescargas,
                    'folleto_popular' => $folletoPopular ? new FolletoResource($folletoPopular) : null,
                    'descargas_por_mes' => $descargasPorMes,
                    'promedio_descargas' => $totalFolletos > 0 ? round($totalDescargas / $totalFolletos, 2) : 0,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
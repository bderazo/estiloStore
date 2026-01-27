<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpresaDatoRequest;
use App\Http\Requests\UpdateEmpresaDatoRequest;
use App\Http\Resources\EmpresaDatoResource;
use App\Models\EmpresaDato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmpresaDatoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = EmpresaDato::query();

            // Búsqueda
            if ($request->has('search') && $request->search) {
                $query->buscar($request->search);
            }

            // Filtro por estado
            if ($request->has('activo') && $request->activo !== null) {
                $query->filtrarPorEstado($request->activo);
            }

            // Ordenación
            $sortBy = $request->get('sort_by', 'orden');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginación
            $perPage = $request->get('per_page', 15);
            $empresaDatos = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => EmpresaDatoResource::collection($empresaDatos),
                'meta' => [
                    'total' => $empresaDatos->total(),
                    'current_page' => $empresaDatos->currentPage(),
                    'last_page' => $empresaDatos->lastPage(),
                    'per_page' => $empresaDatos->perPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos de empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreEmpresaDatoRequest $request)
    {
        // DEBUG: Ver qué está llegando
        \Log::info('📥 Datos recibidos en store:', [
            'all' => $request->all(),
            'activo' => $request->input('activo'),
            'activo_type' => gettype($request->input('activo')),
            'headers' => $request->headers->all(),
            'content_type' => $request->header('Content-Type'),
            'has_imagen' => $request->hasFile('imagen')
        ]);

        \Log::info('🔧 Configuración de base de datos:', [
            'connection' => config('database.default'),
            'database' => config('database.connections.' . config('database.default') . '.database')
        ]);
        
        DB::beginTransaction();
        try {
            // Obtener datos validados
            $data = $request->validated();

            // DEBUG: Ver datos validados
            \Log::info('📝 Datos validados después de validated():', $data);
            \Log::info('📝 Datos validados - activo:', [
                'value' => $data['activo'] ?? 'not set',
                'type' => isset($data['activo']) ? gettype($data['activo']) : 'not set'
            ]);

            // Manejo de imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();
                $path = $imagen->storeAs('empresa_datos', $filename, 'public');
                $data['imagen'] = $path;
            } else {
                \Log::info('📸 No tiene imagen');
            }

            // DEBUG: Ver datos antes de crear
            \Log::info('🗄️ Datos para crear:', $data);
            \Log::info('🗄️ Verificando fillable del modelo:', (new EmpresaDato())->getFillable());

            // Crear el registro
            $empresaDato = EmpresaDato::create($data);
            
            // DEBUG: Verificar si se creó
            \Log::info('✅ Registro creado:', [
                'id' => $empresaDato->id,
                'clave' => $empresaDato->clave,
                'activo' => $empresaDato->activo,
                'created' => $empresaDato->created_at
            ]);

            DB::commit();

            \Log::info('🎉 Transacción completada exitosamente');

            return response()->json([
                'success' => true,
                'message' => 'Dato de empresa creado exitosamente',
                'data' => new EmpresaDatoResource($empresaDato)
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Error en store:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el dato de empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(EmpresaDato $empresaDato)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new EmpresaDatoResource($empresaDato)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dato de empresa no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(UpdateEmpresaDatoRequest $request, EmpresaDato $empresaDato)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Manejo de imagen
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($empresaDato->imagen && Storage::disk('public')->exists($empresaDato->imagen)) {
                    Storage::disk('public')->delete($empresaDato->imagen);
                }

                $path = $request->file('imagen')->store('empresa_datos', 'public');
                $data['imagen'] = $path;
            } elseif ($request->has('remove_imagen') && $request->remove_imagen) {
                // Eliminar imagen si se solicita
                if ($empresaDato->imagen && Storage::disk('public')->exists($empresaDato->imagen)) {
                    Storage::disk('public')->delete($empresaDato->imagen);
                }
                $data['imagen'] = null;
            }

            $empresaDato->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dato de empresa actualizado exitosamente',
                'data' => new EmpresaDatoResource($empresaDato)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el dato de empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(EmpresaDato $empresaDato)
    {
        DB::beginTransaction();
        try {
            // Eliminar imagen si existe
            if ($empresaDato->imagen && Storage::disk('public')->exists($empresaDato->imagen)) {
                Storage::disk('public')->delete($empresaDato->imagen);
            }

            $empresaDato->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dato de empresa eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el dato de empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleActivo(EmpresaDato $empresaDato)
    {
        try {
            $empresaDato->activo = !$empresaDato->activo;
            $empresaDato->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'data' => new EmpresaDatoResource($empresaDato)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getClaves()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => EmpresaDato::getClaves()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las claves',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
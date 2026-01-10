<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Http\Resources\MarcaResource;
use App\Models\Marca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Marca::query();

            // Filtros
            if ($request->has('activo') && $request->activo !== '') {
                $query->where('activo', $request->boolean('activo'));
            }

            if ($request->has('search') && $request->search !== '') {
                $query->where(function($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $request->search . '%')
                      ->orWhere('slug', 'like', '%' . $request->search . '%');
                });
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'nombre');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginación
            $perPage = $request->get('per_page', 15);
            $marcas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Marcas obtenidas correctamente',
                'data' => MarcaResource::collection($marcas->items()),
                'pagination' => [
                    'current_page' => $marcas->currentPage(),
                    'last_page' => $marcas->lastPage(),
                    'per_page' => $marcas->perPage(),
                    'total' => $marcas->total(),
                    'from' => $marcas->firstItem(),
                    'to' => $marcas->lastItem(),
                ],
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las marcas',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Generar slug único
            $data['slug'] = $this->generateUniqueSlug($data['nombre']);

            $marca = Marca::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Marca creada correctamente',
                'data' => new MarcaResource($marca),
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la marca',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Marca obtenida correctamente',
                'data' => new MarcaResource($marca),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la marca',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcaRequest $request, Marca $marca): JsonResponse
    {
        try {
            $data = $request->validated();

            // Regenerar slug si cambió el nombre
            if (isset($data['nombre']) && $data['nombre'] !== $marca->nombre) {
                $data['slug'] = $this->generateUniqueSlug($data['nombre'], $marca->id);
            }

            $marca->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Marca actualizada correctamente',
                'data' => new MarcaResource($marca->fresh()),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la marca',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca): JsonResponse
    {
        try {
            // Verificar si la marca tiene artículos asociados
            // Descomenta cuando tengas el modelo Articulo
            /* if ($marca->articulos()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la marca porque tiene artículos asociados',
                    'data' => null,
                    'errors' => 'La marca tiene artículos asociados'
                ], 422);
            } */

            $marca->delete();

            return response()->json([
                'success' => true,
                'message' => 'Marca eliminada correctamente',
                'data' => null,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la marca',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of the brand.
     */
    public function toggleActivo(Marca $marca): JsonResponse
    {
        try {
            $marca->update([
                'activo' => !$marca->activo
            ]);

            return response()->json([
                'success' => true,
                'message' => $marca->activo ? 'Marca activada correctamente' : 'Marca desactivada correctamente',
                'data' => new MarcaResource($marca->fresh()),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la marca',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get brands for select options.
     */
    public function getForSelect(): JsonResponse
    {
        try {
            $marcas = Marca::active()
                ->orderBy('nombre')
                ->get()
                ->map(function ($marca) {
                    return [
                        'id' => $marca->id,
                        'nombre' => $marca->nombre,
                        'label' => $marca->nombre,
                        'value' => $marca->id
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Marcas para select obtenidas correctamente',
                'data' => $marcas,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las marcas para select',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a unique slug for the brand.
     */
    private function generateUniqueSlug(string $nombre, ?int $excludeId = null): string
    {
        $slug = Str::slug($nombre);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Marca::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

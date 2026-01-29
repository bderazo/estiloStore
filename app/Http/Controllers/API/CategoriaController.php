<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Categoria::with([
                'parent.parent.parent.parent.parent', // Cargar hasta 5 niveles de padres
                'children'
            ])->withCount('children');

            // Filtros
            if ($request->has('activo') && $request->activo !== '') {
                $query->where('activo', $request->boolean('activo'));
            }

            if ($request->has('search') && $request->search !== '') {
                $query->where(function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $request->search . '%')
                        ->orWhere('slug', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->has('parent_id')) {
                if ($request->parent_id === 'null' || $request->parent_id === '') {
                    $query->whereNull('parent_id'); // Solo categorías principales
                } else {
                    $query->where('parent_id', $request->parent_id);
                }
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'orden');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Opción para estructura jerárquica
            if ($request->boolean('hierarchical')) {
                $categorias = $this->getHierarchicalCategories($query);
                return response()->json([
                    'success' => true,
                    'message' => 'Categorías obtenidas correctamente',
                    'data' => CategoriaResource::collection($categorias),
                    'errors' => null
                ]);
            }

            // Paginación
            $perPage = $request->get('per_page', 15);
            $categorias = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Categorías obtenidas correctamente',
                'data' => CategoriaResource::collection($categorias->items()),
                'pagination' => [
                    'current_page' => $categorias->currentPage(),
                    'last_page' => $categorias->lastPage(),
                    'per_page' => $categorias->perPage(),
                    'total' => $categorias->total(),
                    'from' => $categorias->firstItem(),
                    'to' => $categorias->lastItem(),
                ],
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las categorías',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('categorias', 'public');
                $data['imagen'] = $path; // Esto guardará: "categorias/nombre-imagen.jpg"

            }

            if (!isset($data['orden'])) {
                $maxOrden = Categoria::where('parent_id', $data['parent_id'] ?? null)
                    ->max('orden') ?? 0;
                $data['orden'] = $maxOrden + 1;
            }

            $categoria = Categoria::create($data);
            $categoria->load(['parent', 'children']);

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'data' => new CategoriaResource($categoria),
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría: ' . $e->getMessage(),
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria): JsonResponse
    {
        try {
            $categoria->load([
                'parent.parent.parent.parent.parent', // Cargar hasta 5 niveles de padres
                'children'
            ])->loadCount('children');

            return response()->json([
                'success' => true,
                'message' => 'Categoría obtenida correctamente',
                'data' => new CategoriaResource($categoria),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la categoría',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria): JsonResponse
    {
        try {
            $data = $request->validated();

            // Manejar eliminación de imagen
            if ($request->has('imagen_eliminar') && $request->imagen_eliminar) {
                // Eliminar imagen anterior si existe
                if ($categoria->imagen) {
                    Storage::disk('public')->delete($categoria->imagen);
                    $data['imagen'] = null;
                }
            }

            // Manejar nueva imagen si existe
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($categoria->imagen) {
                    Storage::disk('public')->delete($categoria->imagen);
                }

                // Guardar nueva imagen
                $path = $request->file('imagen')->store('categorias', 'public');
                $data['imagen'] = $path;
            }

            // Actualizar categoría
            $categoria->update($data);
            $categoria->load(['parent', 'children']);

            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => new CategoriaResource($categoria),
                'errors' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la categoría: ' . $e->getMessage(),
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria): JsonResponse
    {
        try {
            // Verificar si tiene subcategorías
            if ($categoria->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar una categoría que tiene subcategorías',
                    'data' => null,
                    'errors' => 'La categoría tiene subcategorías asociadas'
                ], 422);
            }

            $categoria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada exitosamente',
                'data' => null,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la categoría',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the activo status of the specified resource.
     */
    public function toggleActivo(Categoria $categoria): JsonResponse
    {
        try {
            $categoria->update(['activo' => !$categoria->activo]);
            $categoria->load(['parent', 'children']);

            return response()->json([
                'success' => true,
                'message' => 'Estado de la categoría actualizado exitosamente',
                'data' => new CategoriaResource($categoria),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la categoría',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories for select options.
     */
    public function getForSelect(Request $request): JsonResponse
    {
        try {
            $query = Categoria::active();

            // Excluir una categoría específica (útil para editar)
            if ($request->has('exclude')) {
                $query->where('id', '!=', $request->exclude);
            }

            $categorias = $query->orderBy('nombre')->get();

            // Estructura jerárquica para select
            $hierarchical = $this->buildSelectHierarchy($categorias);

            return response()->json([
                'success' => true,
                'message' => 'Categorías para select obtenidas correctamente',
                'data' => $hierarchical,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las categorías',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder categories.
     */
    public function reorder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'categories' => 'required|array',
                'categories.*.id' => 'required|exists:categorias,id',
                'categories.*.orden' => 'required|integer|min:0'
            ]);

            foreach ($request->categories as $categoryData) {
                Categoria::where('id', $categoryData['id'])
                    ->update(['orden' => $categoryData['orden']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orden de categorías actualizado exitosamente',
                'data' => null,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reordenar las categorías',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get hierarchical categories structure.
     */
    private function getHierarchicalCategories($query)
    {
        $allCategories = $query->get();
        return $this->buildHierarchy($allCategories);
    }

    /**
     * Build hierarchy from flat collection.
     */
    private function buildHierarchy($categories, $parentId = null, $level = 0)
    {
        $result = collect();

        $filtered = $categories->where('parent_id', $parentId);

        foreach ($filtered as $category) {
            $category->level = $level;
            $children = $this->buildHierarchy($categories, $category->id, $level + 1);
            if ($children->isNotEmpty()) {
                $result->push($category);
                $result = $result->merge($children);
            } else {
                $result->push($category);
            }
        }

        return $result;
    }

    /**
     * Build select hierarchy with indentation.
     */
    private function buildSelectHierarchy($categories, $parentId = null, $prefix = '')
    {
        $result = [];

        $filtered = $categories->where('parent_id', $parentId);

        foreach ($filtered as $category) {
            $result[] = [
                'id' => $category->id,
                'nombre' => $prefix . $category->nombre,
                'value' => $category->id,
                'label' => $prefix . $category->nombre
            ];

            // Agregar subcategorías con indentación
            $children = $this->buildSelectHierarchy($categories, $category->id, $prefix . '└─ ');
            $result = array_merge($result, $children);
        }

        return $result;
    }
}

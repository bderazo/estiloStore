<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaborRequest;
use App\Http\Requests\UpdateSaborRequest;
use App\Http\Resources\SaborResource;
use App\Models\Sabor;
use Illuminate\Http\Request;

class SaborController extends Controller
{
    /**
     * Display a listing of the resource with filters and pagination.
     */
    public function index(Request $request)
    {
        $query = Sabor::query();

        // Filtro de búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%");
        }

        // Filtro de estado
        if ($request->filled('activo')) {
            $activo = filter_var($request->input('activo'), FILTER_VALIDATE_BOOLEAN);
            $query->where('activo', $activo);
        }

        // Ordenamiento
        $sort_by = $request->input('sort_by', 'nombre');
        $sort_order = $request->input('sort_order', 'asc');
        $query->orderBy($sort_by, $sort_order);

        // Paginación
        $per_page = $request->input('per_page', 15);
        $sabores = $query->paginate($per_page);

        return response()->json([
            'success' => true,
            'message' => 'Sabores obtenidos correctamente',
            'data' => SaborResource::collection($sabores->items()),
            'pagination' => [
                'current_page' => $sabores->currentPage(),
                'last_page' => $sabores->lastPage(),
                'per_page' => $sabores->perPage(),
                'total' => $sabores->total(),
                'from' => $sabores->firstItem(),
                'to' => $sabores->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaborRequest $request)
    {
        try {
            $sabor = Sabor::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sabor creado correctamente',
                'data' => new SaborResource($sabor),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el sabor: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sabor $sabor)
    {
        return response()->json([
            'success' => true,
            'message' => 'Sabor obtenido correctamente',
            'data' => new SaborResource($sabor),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaborRequest $request, Sabor $sabor)
    {
        try {
            $sabor->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sabor actualizado correctamente',
                'data' => new SaborResource($sabor),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el sabor: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sabor $sabor)
    {
        try {
            $sabor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sabor eliminado correctamente',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el sabor: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Toggle the active state of the specified resource.
     */
    public function toggleActivo(Sabor $sabor)
    {
        try {
            $sabor->update([
                'activo' => !$sabor->activo,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado del sabor actualizado correctamente',
                'data' => new SaborResource($sabor),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del sabor: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Get sabores for select dropdowns.
     */
    public function getForSelect()
    {
        $sabores = Sabor::active()->select('id', 'nombre')->get();

        return response()->json([
            'success' => true,
            'message' => 'Sabores obtenidos correctamente',
            'data' => $sabores->map(fn ($sabor) => [
                'id' => $sabor->id,
                'nombre' => $sabor->nombre,
                'label' => $sabor->nombre,
                'value' => $sabor->id,
            ]),
        ]);
    }
}

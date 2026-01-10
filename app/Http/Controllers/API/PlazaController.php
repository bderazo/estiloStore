<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlazaRequest;
use App\Http\Requests\UpdatePlazaRequest;
use App\Http\Resources\PlazaResource;
use App\Models\Plaza;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlazaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Plaza::query();

            // Filtros
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('nombre', 'like', "%{$search}%");
            }

            if ($request->has('activo') && $request->input('activo') !== '') {
                $query->where('activo', $request->boolean('activo'));
            }

            // Ordenamiento
            $sortBy = $request->input('sort_by', 'nombre');
            $sortOrder = $request->input('sort_order', 'asc');

            if (in_array($sortBy, ['nombre', 'created_at', 'updated_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Paginación
            $perPage = $request->input('per_page', 15);
            $plazas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Plazas obtenidas correctamente',
                'data' => PlazaResource::collection($plazas->items()),
                'pagination' => [
                    'current_page' => $plazas->currentPage(),
                    'last_page' => $plazas->lastPage(),
                    'per_page' => $plazas->perPage(),
                    'total' => $plazas->total(),
                    'from' => $plazas->firstItem(),
                    'to' => $plazas->lastItem(),
                ],
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las plazas',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlazaRequest $request): JsonResponse
    {
        try {
            $plaza = Plaza::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Plaza creada correctamente',
                'data' => new PlazaResource($plaza),
                'errors' => null
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la plaza',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Plaza $plaza): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Plaza obtenida correctamente',
                'data' => new PlazaResource($plaza),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la plaza',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlazaRequest $request, Plaza $plaza): JsonResponse
    {
        try {
            $plaza->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Plaza actualizada correctamente',
                'data' => new PlazaResource($plaza->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la plaza',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plaza $plaza): JsonResponse
    {
        try {
            $plaza->delete();

            return response()->json([
                'success' => true,
                'message' => 'Plaza eliminada correctamente',
                'data' => null,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la plaza',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActivo(Plaza $plaza): JsonResponse
    {
        try {
            $plaza->update(['activo' => !$plaza->activo]);

            return response()->json([
                'success' => true,
                'message' => $plaza->activo ? 'Plaza activada correctamente' : 'Plaza desactivada correctamente',
                'data' => new PlazaResource($plaza->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la plaza',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get plazas for select/dropdown components.
     */
    public function getForSelect(): JsonResponse
    {
        try {
            $plazas = Plaza::active()
                ->orderBy('nombre')
                ->get()
                ->map(function ($plaza) {
                    return [
                        'id' => $plaza->id,
                        'nombre' => $plaza->nombre,
                        'label' => $plaza->nombre,
                        'value' => $plaza->id,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Plazas para select obtenidas correctamente',
                'data' => $plazas,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las plazas para select',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}

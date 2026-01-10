<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedidaRequest;
use App\Http\Requests\UpdateMedidaRequest;
use App\Http\Resources\MedidaResource;
use App\Models\Medida;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Medida::query();

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
            $medidas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Medidas obtenidas correctamente',
                'data' => MedidaResource::collection($medidas->items()),
                'pagination' => [
                    'current_page' => $medidas->currentPage(),
                    'last_page' => $medidas->lastPage(),
                    'per_page' => $medidas->perPage(),
                    'total' => $medidas->total(),
                    'from' => $medidas->firstItem(),
                    'to' => $medidas->lastItem(),
                ],
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las medidas',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedidaRequest $request): JsonResponse
    {
        try {
            $medida = Medida::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Medida creada correctamente',
                'data' => new MedidaResource($medida),
                'errors' => null
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la medida',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Medida $medida): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Medida obtenida correctamente',
                'data' => new MedidaResource($medida),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la medida',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedidaRequest $request, Medida $medida): JsonResponse
    {
        try {
            $medida->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Medida actualizada correctamente',
                'data' => new MedidaResource($medida->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la medida',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medida $medida): JsonResponse
    {
        try {
            $medida->delete();

            return response()->json([
                'success' => true,
                'message' => 'Medida eliminada correctamente',
                'data' => null,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la medida',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActivo(Medida $medida): JsonResponse
    {
        try {
            $medida->update(['activo' => !$medida->activo]);

            return response()->json([
                'success' => true,
                'message' => $medida->activo ? 'Medida activada correctamente' : 'Medida desactivada correctamente',
                'data' => new MedidaResource($medida->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la medida',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get medidas for select/dropdown components.
     */
    public function getForSelect(): JsonResponse
    {
        try {
            $medidas = Medida::active()
                ->orderBy('nombre')
                ->get()
                ->map(function ($medida) {
                    return [
                        'id' => $medida->id,
                        'nombre' => $medida->nombre,
                        'label' => $medida->nombre,
                        'value' => $medida->id,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Medidas para select obtenidas correctamente',
                'data' => $medidas,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las medidas para select',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}

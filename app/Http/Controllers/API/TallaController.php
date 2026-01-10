<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTallaRequest;
use App\Http\Requests\UpdateTallaRequest;
use App\Http\Resources\TallaResource;
use App\Models\Talla;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TallaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Talla::query();

            // Filtros
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%");
                });
            }

            if ($request->has('activo') && $request->input('activo') !== '') {
                $query->where('activo', $request->boolean('activo'));
            }

            // Ordenamiento
            $sortBy = $request->input('sort_by', 'nombre');
            $sortOrder = $request->input('sort_order', 'asc');

            if (in_array($sortBy, ['nombre', 'descripcion', 'created_at', 'updated_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Paginación
            $perPage = $request->input('per_page', 15);
            $tallas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Tallas obtenidas correctamente',
                'data' => TallaResource::collection($tallas->items()),
                'pagination' => [
                    'current_page' => $tallas->currentPage(),
                    'last_page' => $tallas->lastPage(),
                    'per_page' => $tallas->perPage(),
                    'total' => $tallas->total(),
                    'from' => $tallas->firstItem(),
                    'to' => $tallas->lastItem(),
                ],
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las tallas',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTallaRequest $request): JsonResponse
    {
        try {
            $talla = Talla::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Talla creada correctamente',
                'data' => new TallaResource($talla),
                'errors' => null
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la talla',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Talla $talla): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Talla obtenida correctamente',
                'data' => new TallaResource($talla),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la talla',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTallaRequest $request, Talla $talla): JsonResponse
    {
        try {
            $talla->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Talla actualizada correctamente',
                'data' => new TallaResource($talla->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la talla',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Talla $talla): JsonResponse
    {
        try {
            $talla->delete();

            return response()->json([
                'success' => true,
                'message' => 'Talla eliminada correctamente',
                'data' => null,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la talla',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActivo(Talla $talla): JsonResponse
    {
        try {
            $talla->update(['activo' => !$talla->activo]);

            return response()->json([
                'success' => true,
                'message' => $talla->activo ? 'Talla activada correctamente' : 'Talla desactivada correctamente',
                'data' => new TallaResource($talla->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la talla',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tallas for select/dropdown components.
     */
    public function getForSelect(): JsonResponse
    {
        try {
            $tallas = Talla::active()
                ->orderBy('nombre')
                ->get()
                ->map(function ($talla) {
                    return [
                        'id' => $talla->id,
                        'nombre' => $talla->nombre,
                        'descripcion' => $talla->descripcion,
                        'label' => $talla->descripcion
                            ? "{$talla->nombre} - {$talla->descripcion}"
                            : $talla->nombre,
                        'value' => $talla->id,
                        'nombre_completo' => $talla->descripcion
                            ? "{$talla->nombre} - {$talla->descripcion}"
                            : $talla->nombre
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Tallas para select obtenidas correctamente',
                'data' => $tallas,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las tallas para select',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}

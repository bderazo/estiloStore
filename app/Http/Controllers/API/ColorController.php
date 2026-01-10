<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Color::query();

            // Filtros
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('codigo_hex', 'like', "%{$search}%");
                });
            }

            if ($request->has('activo') && $request->input('activo') !== '') {
                $query->where('activo', $request->boolean('activo'));
            }

            // Ordenamiento
            $sortBy = $request->input('sort_by', 'nombre');
            $sortOrder = $request->input('sort_order', 'asc');

            if (in_array($sortBy, ['nombre', 'codigo_hex', 'created_at', 'updated_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Paginación
            $perPage = $request->input('per_page', 15);
            $colores = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Colores obtenidos correctamente',
                'data' => ColorResource::collection($colores->items()),
                'pagination' => [
                    'current_page' => $colores->currentPage(),
                    'last_page' => $colores->lastPage(),
                    'per_page' => $colores->perPage(),
                    'total' => $colores->total(),
                    'from' => $colores->firstItem(),
                    'to' => $colores->lastItem(),
                ],
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los colores',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColorRequest $request): JsonResponse
    {
        try {
            $color = Color::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Color creado correctamente',
                'data' => new ColorResource($color),
                'errors' => null
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el color',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Color obtenido correctamente',
                'data' => new ColorResource($color),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el color',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorRequest $request, Color $color): JsonResponse
    {
        try {
            $color->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Color actualizado correctamente',
                'data' => new ColorResource($color->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el color',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color): JsonResponse
    {
        try {
            $color->delete();

            return response()->json([
                'success' => true,
                'message' => 'Color eliminado correctamente',
                'data' => null,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el color',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActivo(Color $color): JsonResponse
    {
        try {
            $color->update(['activo' => !$color->activo]);

            return response()->json([
                'success' => true,
                'message' => $color->activo ? 'Color activado correctamente' : 'Color desactivado correctamente',
                'data' => new ColorResource($color->fresh()),
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del color',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get colors for select/dropdown components.
     */
    public function getForSelect(): JsonResponse
    {
        try {
            $colores = Color::active()
                ->orderBy('nombre')
                ->get()
                ->map(function ($color) {
                    return [
                        'id' => $color->id,
                        'nombre' => $color->nombre,
                        'codigo_hex' => $color->codigo_hex,
                        'label' => "{$color->nombre} ({$color->codigo_hex})",
                        'value' => $color->id,
                        'color_preview' => [
                            'background' => $color->codigo_hex,
                            'contrast' => $this->getContrastColor($color->codigo_hex)
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Colores para select obtenidos correctamente',
                'data' => $colores,
                'errors' => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los colores para select',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener color de contraste para el texto
     */
    private function getContrastColor(string $hexColor): string
    {
        // Remover el #
        $hex = str_replace('#', '', $hexColor);

        // Convertir a RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calcular luminancia
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Retornar blanco o negro según la luminancia
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }
}

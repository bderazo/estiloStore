<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarruselRequest;
use App\Http\Requests\UpdateCarruselRequest;
use App\Http\Resources\CarruselResource;
use App\Models\Carrusel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarruselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Carrusel::query();

            // Filtros
            if ($request->has('estado') && $request->estado !== '') {
                $query->where('estado', $request->boolean('estado'));
            }

            if ($request->has('search') && $request->search !== '') {
                $query->where(function($q) use ($request) {
                    $q->where('titulo', 'like', '%' . $request->search . '%')
                      ->orWhere('subtitulo', 'like', '%' . $request->search . '%');
                });
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginación
            $perPage = $request->get('per_page', 10);
            $carruseles = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Carruseles obtenidos correctamente',
                'data' => CarruselResource::collection($carruseles->items()),
                'pagination' => [
                    'current_page' => $carruseles->currentPage(),
                    'last_page' => $carruseles->lastPage(),
                    'per_page' => $carruseles->perPage(),
                    'total' => $carruseles->total(),
                    'from' => $carruseles->firstItem(),
                    'to' => $carruseles->lastItem(),
                ],
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los carruseles',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarruselRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Manejar la subida de imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();
                $path = $imagen->storeAs('carrusel', $filename, 'public');
                $data['imagen'] = $path;
            }

            $carrusel = Carrusel::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Carrusel creado exitosamente',
                'data' => new CarruselResource($carrusel),
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el carrusel',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Carrusel $carrusel): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Carrusel obtenido correctamente',
                'data' => new CarruselResource($carrusel),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el carrusel',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarruselRequest $request, Carrusel $carrusel): JsonResponse
    {
        try {
            $data = $request->validated();

            // Manejar la subida de nueva imagen
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($carrusel->imagen && Storage::disk('public')->exists($carrusel->imagen)) {
                    Storage::disk('public')->delete($carrusel->imagen);
                }

                $imagen = $request->file('imagen');
                $filename = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();
                $path = $imagen->storeAs('carrusel', $filename, 'public');
                $data['imagen'] = $path;
            }

            $carrusel->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Carrusel actualizado exitosamente',
                'data' => new CarruselResource($carrusel->fresh()),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el carrusel',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carrusel $carrusel): JsonResponse
    {
        try {
            // Eliminar imagen asociada
            if ($carrusel->imagen && Storage::disk('public')->exists($carrusel->imagen)) {
                Storage::disk('public')->delete($carrusel->imagen);
            }

            $carrusel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Carrusel eliminado exitosamente',
                'data' => null,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el carrusel',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the estado of the specified resource.
     */
    public function toggleEstado(Carrusel $carrusel): JsonResponse
    {
        try {
            $carrusel->update(['estado' => !$carrusel->estado]);

            return response()->json([
                'success' => true,
                'message' => 'Estado del carrusel actualizado exitosamente',
                'data' => new CarruselResource($carrusel->fresh()),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del carrusel',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get carrusel positions for select options.
     */
    public function getPosiciones(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Posiciones obtenidas correctamente',
                'data' => Carrusel::POSICIONES,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las posiciones',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}

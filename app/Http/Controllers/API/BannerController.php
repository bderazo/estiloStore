<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Banner::query();

            // Filtros
            if ($request->has('estado') && $request->estado !== '') {
                $query->where('estado', $request->boolean('estado'));
            }

            if ($request->has('seccion') && $request->seccion !== '') {
                $query->where('seccion', $request->seccion);
            }

            if ($request->has('search') && $request->search !== '') {
                $query->where(function($q) use ($request) {
                    $q->where('titulo', 'like', '%' . $request->search . '%')
                      ->orWhere('subtitulo', 'like', '%' . $request->search . '%')
                      ->orWhere('seccion', 'like', '%' . $request->search . '%');
                });
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginación
            $perPage = $request->get('per_page', 10);
            $banners = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Banners obtenidos correctamente',
                'data' => BannerResource::collection($banners->items()),
                'pagination' => [
                    'current_page' => $banners->currentPage(),
                    'last_page' => $banners->lastPage(),
                    'per_page' => $banners->perPage(),
                    'total' => $banners->total(),
                    'from' => $banners->firstItem(),
                    'to' => $banners->lastItem(),
                ],
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los banners',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Manejar la subida de imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();
                $path = $imagen->storeAs('banners', $filename, 'public');
                $data['imagen_ruta'] = $path;
            }

            $banner = Banner::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Banner creado exitosamente',
                'data' => new BannerResource($banner),
                'errors' => null
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el banner',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Banner obtenido correctamente',
                'data' => new BannerResource($banner),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el banner',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner): JsonResponse
    {
        try {
            $data = $request->validated();

            // Manejar la subida de nueva imagen
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($banner->imagen_ruta && Storage::disk('public')->exists($banner->imagen_ruta)) {
                    Storage::disk('public')->delete($banner->imagen_ruta);
                }

                $imagen = $request->file('imagen');
                $filename = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();
                $path = $imagen->storeAs('banners', $filename, 'public');
                $data['imagen_ruta'] = $path;
            }

            $banner->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Banner actualizado exitosamente',
                'data' => new BannerResource($banner->fresh()),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el banner',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): JsonResponse
    {
        try {
            // Eliminar imagen asociada
            if ($banner->imagen_ruta && Storage::disk('public')->exists($banner->imagen_ruta)) {
                Storage::disk('public')->delete($banner->imagen_ruta);
            }

            $banner->delete();

            return response()->json([
                'success' => true,
                'message' => 'Banner eliminado exitosamente',
                'data' => null,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el banner',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the estado of the specified resource.
     */
    public function toggleEstado(Banner $banner): JsonResponse
    {
        try {
            $banner->update(['estado' => !$banner->estado]);

            return response()->json([
                'success' => true,
                'message' => 'Estado del banner actualizado exitosamente',
                'data' => new BannerResource($banner->fresh()),
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del banner',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get banner sections for select options.
     */
    public function getSecciones(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Secciones obtenidas correctamente',
                'data' => Banner::SECCIONES,
                'errors' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las secciones',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
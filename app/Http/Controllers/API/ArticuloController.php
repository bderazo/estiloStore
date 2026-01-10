<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Articulo\StoreArticuloRequest;
use App\Http\Requests\Articulo\UpdateArticuloRequest;
use App\Http\Requests\Articulo\StoreArticuloVarianteRequest;
use App\Http\Requests\Articulo\UpdateArticuloVarianteRequest;
use App\Models\Articulo;
use App\Models\ArticuloVariante;
use App\Services\ArticuloService;
use App\Services\ArticuloVarianteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticuloController extends Controller
{
    protected ArticuloService $articuloService;
    protected ArticuloVarianteService $varianteService;

    public function __construct(
        ArticuloService $articuloService,
        ArticuloVarianteService $varianteService
    ) {
        $this->articuloService = $articuloService;
        $this->varianteService = $varianteService;
    }

    /**
     * Listar artículos con filtros y paginación
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'q',
                'categoria_id',
                'marca_id',
                'activo',
                'destacado',
                'precio_min',
                'precio_max',
                'orden_por',
                'direccion',
                'per_page',
            ]);

            $perPage = $request->input('per_page', 15);
            $articulos = $this->articuloService->getArticulos($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => $articulos->items(),
                'pagination' => [
                    'total' => $articulos->total(),
                    'per_page' => $articulos->perPage(),
                    'current_page' => $articulos->currentPage(),
                    'last_page' => $articulos->lastPage(),
                    'from' => $articulos->firstItem(),
                    'to' => $articulos->lastItem(),
                ],
                'message' => 'Artículos obtenidos correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener artículos: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Obtener artículo individual
     */
    public function show(Articulo $articulo): JsonResponse
    {
        try {
            $articulo = $this->articuloService->getArticulo($articulo->id);

            return response()->json([
                'success' => true,
                'data' => $articulo,
                'message' => 'Artículo obtenido correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener artículo: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Crear nuevo artículo
     */
    public function store(StoreArticuloRequest $request): JsonResponse
    {
        try {
            $articulo = $this->articuloService->crearArticulo($request->validated());

            return response()->json([
                'success' => true,
                'data' => $articulo,
                'message' => 'Artículo creado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear artículo: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Actualizar artículo
     */
    public function update(UpdateArticuloRequest $request, Articulo $articulo): JsonResponse
    {
        try {
            $datos = $request->validated();

            $articulo = $this->articuloService->actualizarArticulo(
                $articulo,
                $datos
            );

            return response()->json([
                'success' => true,
                'data' => $articulo,
                'message' => 'Artículo actualizado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar artículo: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Eliminar artículo (soft delete)
     */
    public function destroy(Articulo $articulo): JsonResponse
    {
        try {
            $this->articuloService->eliminarArticulo($articulo);

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Artículo eliminado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar artículo: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Activar/desactivar artículo
     */
    public function toggleActivo(Articulo $articulo): JsonResponse
    {
        try {
            $articulo = $this->articuloService->toggleActivo($articulo);

            return response()->json([
                'success' => true,
                'data' => $articulo,
                'message' => 'Estado de artículo actualizado',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar artículo: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Destacar/des-destacar artículo
     */
    public function toggleDestacado(Articulo $articulo): JsonResponse
    {
        try {
            $articulo = $this->articuloService->toggleDestacado($articulo);

            return response()->json([
                'success' => true,
                'data' => $articulo,
                'message' => 'Estado destacado actualizado',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar artículo: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Crear variante para artículo
     */
    public function storeVariante(
        StoreArticuloVarianteRequest $request,
        Articulo $articulo
    ): JsonResponse {
        try {
            $variante = $articulo->variantes()->create($request->validated());

            return response()->json([
                'success' => true,
                'data' => $variante,
                'message' => 'Variante creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear variante: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Actualizar variante
     */
    public function updateVariante(
        UpdateArticuloVarianteRequest $request,
        Articulo $articulo,
        ArticuloVariante $variante
    ): JsonResponse {
        try {
            // Validar que la variante pertenece al artículo
            if ($variante->articulo_id !== $articulo->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La variante no pertenece a este artículo',
                    'errors' => [],
                ], 404);
            }

            $variante->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $variante,
                'message' => 'Variante actualizada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar variante: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Eliminar variante
     */
    public function destroyVariante(
        Articulo $articulo,
        ArticuloVariante $variante
    ): JsonResponse {
        try {
            if ($variante->articulo_id !== $articulo->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La variante no pertenece a este artículo',
                    'errors' => [],
                ], 404);
            }

            $variante->delete();

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Variante eliminada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar variante: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }

    /**
     * Reservar stock para pre-venta
     */
    public function reservarStock(
        Request $request,
        ArticuloVariante $variante
    ): JsonResponse {
        try {
            $request->validate(['cantidad' => 'required|integer|min:1']);

            $this->varianteService->reservar($variante, $request->input('cantidad'));
            $variante->refresh();

            return response()->json([
                'success' => true,
                'data' => $this->varianteService->obtenerInfoStock($variante),
                'message' => 'Stock reservado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 400);
        }
    }

    /**
     * Liberar reserva
     */
    public function liberarStock(
        Request $request,
        ArticuloVariante $variante
    ): JsonResponse {
        try {
            $request->validate(['cantidad' => 'required|integer|min:1']);

            $this->varianteService->liberar($variante, $request->input('cantidad'));
            $variante->refresh();

            return response()->json([
                'success' => true,
                'data' => $this->varianteService->obtenerInfoStock($variante),
                'message' => 'Reserva liberada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 400);
        }
    }

    /**
     * Decrementar stock confirmando venta
     */
    public function decrementarStock(
        Request $request,
        ArticuloVariante $variante
    ): JsonResponse {
        try {
            $request->validate(['cantidad' => 'required|integer|min:1']);

            $this->varianteService->decrementar($variante, $request->input('cantidad'));
            $variante->refresh();

            return response()->json([
                'success' => true,
                'data' => $this->varianteService->obtenerInfoStock($variante),
                'message' => 'Stock decrementado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 400);
        }
    }

    /**
     * Obtener información de stock
     */
    public function infoStock(ArticuloVariante $variante): JsonResponse
    {
        try {
            $info = $this->varianteService->obtenerInfoStock($variante);

            return response()->json([
                'success' => true,
                'data' => $info,
                'message' => 'Información de stock obtenida',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }
}

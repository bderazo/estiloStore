<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModeloRequest;
use App\Http\Requests\UpdateModeloRequest;
use App\Http\Resources\ModeloResource;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Modelo::query();

        if ($request->has('search') && $request->get('search') !== '') {
            $search = $request->get('search');
            $query->where('nombre', 'like', "%{$search}%");
        }

        if ($request->has('activo') && $request->get('activo') != '') {
            $query->where('activo', (bool) $request->get('activo'));
        }

        $perPage = $request->get('per_page', 10);
        $modelos = $query->orderBy('nombre', 'asc')->paginate($perPage);

        return response()->json([
            'data' => ModeloResource::collection($modelos->items()),
            'pagination' => [
                'current_page' => $modelos->currentPage(),
                'total' => $modelos->total(),
                'per_page' => $modelos->perPage(),
                'last_page' => $modelos->lastPage(),
                'from' => $modelos->firstItem(),
                'to' => $modelos->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModeloRequest $request)
    {
        try {
            $modelo = Modelo::create($request->validated());

            return response()->json(
                new ModeloResource($modelo),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el modelo',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Modelo $modelo)
    {
        return response()->json(new ModeloResource($modelo));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModeloRequest $request, Modelo $modelo)
    {
        try {
            $modelo->update($request->validated());

            return response()->json(new ModeloResource($modelo));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el modelo',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modelo $modelo)
    {
        try {
            $modelo->delete();

            return response()->json([
                'message' => 'Modelo eliminado correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el modelo',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActivo(Modelo $modelo)
    {
        try {
            $modelo->update([
                'activo' => !$modelo->activo,
            ]);

            return response()->json(new ModeloResource($modelo));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar el estado del modelo',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all modelos for select.
     */
    public function getForSelect()
    {
        $modelos = Modelo::where('activo', true)
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre']);

        return response()->json([
            'data' => $modelos,
        ]);
    }
}

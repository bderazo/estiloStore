<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTonoRequest;
use App\Http\Requests\UpdateTonoRequest;
use App\Http\Resources\TonoResource;
use App\Models\Tono;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TonoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tono::query();

        if ($request->has('search') && $request->get('search') !== '') {
          //  $search = $request->get('search');
           // $query->where('nombre', 'like', "%{$search}%");
        }

        if ($request->has('activo') && $request->get('activo') != "" && $request->get('activo') != NULL) {
          //  dd($request->get('activo'));
        //    $query->where('activo', (bool) $request->get('activo'));
        }

        $perPage = $request->get('per_page', 10);
        $tonos = $query->orderBy('nombre', 'asc')->paginate($perPage);

        return response()->json([
            'data' => TonoResource::collection($tonos->items()),
            'pagination' => [
                'current_page' => $tonos->currentPage(),
                'total' => $tonos->total(),
                'per_page' => $tonos->perPage(),
                'last_page' => $tonos->lastPage(),
                'from' => $tonos->firstItem(),
                'to' => $tonos->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTonoRequest $request)
    {
        try {
            $tono = Tono::create($request->validated());

            return response()->json(
                new TonoResource($tono),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el tono',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tono $tono)
    {
        return response()->json(new TonoResource($tono));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTonoRequest $request, Tono $tono)
    {
        try {
            $tono->update($request->validated());

            return response()->json(new TonoResource($tono));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el tono',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tono $tono)
    {
        try {
            $tono->delete();

            return response()->json([
                'message' => 'Tono eliminado correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el tono',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActivo(Tono $tono)
    {
        try {
            $tono->update([
                'activo' => !$tono->activo,
            ]);

            return response()->json(new TonoResource($tono));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cambiar el estado del tono',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all tonos for select.
     */
    public function getForSelect()
    {
        $tonos = Tono::where('activo', true)
            ->orderBy('nombre', 'asc')
            ->get(['id', 'nombre']);

        return response()->json([
            'data' => $tonos,
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\StoreModuleRequest;
use App\Http\Requests\Modules\UpdateModuleRequest;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Module::with(['permissions']);
        
        // Filtro por búsqueda
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
        }

        // Paginación
        $perPage = $request->get('per_page', 10);
        $modules = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Lista de módulos obtenida correctamente',
            'data' => [
                'modules' => $modules->items(),
                'pagination' => [
                    'current_page' => $modules->currentPage(),
                    'last_page' => $modules->lastPage(),
                    'per_page' => $modules->perPage(),
                    'total' => $modules->total(),
                ]
            ],
            'errors' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModuleRequest $request): JsonResponse
    {
        $module = Module::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Módulo creado correctamente',
            'data' => ['module' => $module],
            'errors' => null
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module): JsonResponse
    {
        $module->load('permissions');

        return response()->json([
            'success' => true,
            'message' => 'Módulo obtenido correctamente',
            'data' => ['module' => $module],
            'errors' => null
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModuleRequest $request, Module $module): JsonResponse
    {
        $module->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Módulo actualizado correctamente',
            'data' => ['module' => $module],
            'errors' => null
        ]);
    }

    /**
     * Get all modules and permissions without pagination for forms.
     */
    public function all(): JsonResponse
    {
        $modules = Module::with(['permissions'])->orderBy('nombre')->get();
        $permissions = $modules->pluck('permissions')->flatten();

        return response()->json([
            'success' => true,
            'message' => 'Módulos y permisos obtenidos correctamente',
            'data' => [
                'modules' => $modules,
                'permissions' => $permissions
            ],
            'errors' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module): JsonResponse
    {
        // Verificar que no sea el módulo inicio
        if ($module->slug === 'inicio') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el módulo de inicio',
                'data' => null,
                'errors' => ['module' => ['El módulo de inicio no puede ser eliminado']]
            ], 422);
        }

        $module->delete();

        return response()->json([
            'success' => true,
            'message' => 'Módulo eliminado correctamente',
            'data' => null,
            'errors' => null
        ]);
    }
}
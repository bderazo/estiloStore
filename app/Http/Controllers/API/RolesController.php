<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Role::with(['permissions.module']);

        // Filtro por búsqueda
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
        }

        // Paginación
        $perPage = $request->get('per_page', 10);
        $roles = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Lista de roles obtenida correctamente',
            'data' => [
                'data' => $roles->items(),
                'pagination' => [
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                    'per_page' => $roles->perPage(),
                    'total' => $roles->total(),
                ]
            ],
            'errors' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());

        // Asignar permisos si se proporcionan
        if ($request->has('permissions')) {
            $permissionIds = Permission::whereIn('id', $request->permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        $role->load('permissions.module');

        return response()->json([
            'success' => true,
            'message' => 'Rol creado correctamente',
            'data' => ['role' => $role],
            'errors' => null
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        $role->load('permissions.module');

        return response()->json([
            'success' => true,
            'message' => 'Rol obtenido correctamente',
            'data' => ['role' => $role],
            'errors' => null
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update($request->validated());

        // Actualizar permisos si se proporcionan
        if ($request->has('permissions')) {
            $permissionIds = Permission::whereIn('id', $request->permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        $role->load('permissions.module');

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado correctamente',
            'data' => ['role' => $role],
            'errors' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        // Verificar que no sea el rol admin
        if ($role->nombre === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el rol de administrador',
                'data' => null,
                'errors' => ['role' => ['El rol de administrador no puede ser eliminado']]
            ], 422);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rol eliminado correctamente',
            'data' => null,
            'errors' => null
        ]);
    }

    /**
     * Obtener permisos agrupados por módulo
     */
    public function getPermissionsByModule(): JsonResponse
    {
        $modules = Module::with('permissions')->get();

        return response()->json([
            'success' => true,
            'message' => 'Permisos agrupados por módulo obtenidos correctamente',
            'data' => ['modules' => $modules],
            'errors' => null
        ]);
    }

    /**
     * Asignar permisos a un rol
     */
    public function assignPermissions(Request $request, Role $role): JsonResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'permissions.required' => 'Debe seleccionar al menos un permiso.',
            'permissions.array' => 'Los permisos deben ser un arreglo válido.',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no existen.',
        ]);

        $permissionIds = Permission::whereIn('id', $request->permissions)->pluck('id');
        $role->permissions()->sync($permissionIds);

        $role->load('permissions.module');

        return response()->json([
            'success' => true,
            'message' => 'Permisos asignados correctamente',
            'data' => ['role' => $role],
            'errors' => null
        ]);
    }

    /**
     * Obtener todos los roles para selects (sin paginación)
     */
    public function all(): JsonResponse
    {
        $roles = Role::select('id', 'nombre', 'descripcion')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Roles obtenidos correctamente',
            'data' => $roles,
            'errors' => null
        ]);
    }
}

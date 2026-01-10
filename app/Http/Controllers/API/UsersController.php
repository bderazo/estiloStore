<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with(['role']);
        
        // Filtro por búsqueda
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero_documento', 'LIKE', "%{$search}%")
                  ->orWhere('nombres', 'LIKE', "%{$search}%")
                  ->orWhere('apellidos', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Lista de usuarios obtenida correctamente',
            'data' => [
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ],
            'errors' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make($userData['password']);
        
        // Asignar rol si se proporciona
        if ($request->has('roles') && !empty($request->roles)) {
            // Tomar solo el primer rol (uno-a-muchos)
            $userData['role_id'] = $request->roles[0];
        }
        
        $user = User::create($userData);
        
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'data' => ['user' => $user],
            'errors' => null
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['role.permissions.module']);

        return response()->json([
            'success' => true,
            'message' => 'Usuario obtenido correctamente',
            'data' => ['user' => $user],
            'errors' => null
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $userData = $request->validated();
        
        // Solo actualizar la contraseña si se proporciona
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }
        
        // Actualizar rol si se proporciona
        if ($request->has('roles')) {
            if (!empty($request->roles)) {
                // Tomar solo el primer rol (uno-a-muchos)
                $userData['role_id'] = $request->roles[0];
            } else {
                $userData['role_id'] = null;
            }
        }
        
        $user->update($userData);
        
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'data' => ['user' => $user],
            'errors' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        // Verificar que no sea el usuario autenticado
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario',
                'data' => null,
                'errors' => ['user' => ['No es posible eliminar el usuario actual']]
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente',
            'data' => null,
            'errors' => null
        ]);
    }
}
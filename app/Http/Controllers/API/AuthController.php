<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Login del usuario
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales inválidas',
                    'data' => null,
                    'errors' => ['auth' => ['Las credenciales proporcionadas son incorrectas']]
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el token',
                'data' => null,
                'errors' => ['server' => ['Error interno del servidor']]
            ], 500);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $rol_nombre = $user->loadMissing('role')->role->nombre;
        $permissions = $user->getAllPermissions();

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
                'user' => [
                    'id' => $user->id,
                    'numero_documento' => $user->numero_documento,
                    'nombres' => $user->nombres,
                    'apellidos' => $user->apellidos,
                    'email' => $user->email,
                    'nombre_completo' => $user->nombre_completo,
                    'rol' => $rol_nombre,
                    'permissions' => $permissions->map(function ($permission) {
                        return [
                            'slug' => $permission->slug,
                            'nombre' => $permission->nombre,
                            'module' => $permission->module->nombre
                        ];
                    })
                ]
            ],
            'errors' => null
        ]);
    }

    /**
     * Obtener información del usuario autenticado
     */
    public function me(): JsonResponse
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado',
                    'data' => null,
                    'errors' => ['auth' => ['Token inválido']]
                ], 404);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido',
                'data' => null,
                'errors' => ['auth' => ['Token no válido o expirado']]
            ], 401);
        }

        $permissions = $user->getAllPermissions();

        return response()->json([
            'success' => true,
            'message' => 'Información del usuario obtenida',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'numero_documento' => $user->numero_documento,
                    'nombres' => $user->nombres,
                    'apellidos' => $user->apellidos,
                    'email' => $user->email,
                    'nombre_completo' => $user->nombre_completo,
                    'roles' => $user->getRolesCollection()->pluck('nombre'),
                    'permissions' => $permissions->map(function ($permission) {
                        return [
                            'slug' => $permission->slug,
                            'nombre' => $permission->nombre,
                            'module' => $permission->module->nombre
                        ];
                    })
                ]
            ],
            'errors' => null
        ]);
    }

    /**
     * Refrescar token JWT
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Token renovado exitosamente',
                'data' => [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                ],
                'errors' => null
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo refrescar el token',
                'data' => null,
                'errors' => ['auth' => ['Token no válido para renovar']]
            ], 401);
        }
    }

    /**
     * Logout del usuario
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
                'data' => null,
                'errors' => null
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo cerrar la sesión',
                'data' => null,
                'errors' => ['auth' => ['Error al invalidar el token']]
            ], 500);
        }
    }
}

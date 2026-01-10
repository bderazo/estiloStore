<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        try {
            // Verificar si el token es válido
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido',
                    'data' => null,
                    'errors' => ['auth' => ['Token no válido']]
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado o inválido',
                'data' => null,
                'errors' => ['auth' => ['Token requerido']]
            ], 401);
        }

        // Verificar si el usuario tiene el permiso requerido
        if (!$user->hasPermission($permission)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos suficientes para realizar esta acción',
                'data' => null,
                'errors' => ['permission' => ['Acceso denegado']]
            ], 403);
        }

        return $next($request);
    }
}
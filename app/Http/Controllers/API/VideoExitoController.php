<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VideoExito;
use App\Models\VideoExitoConfig;
use App\Services\VideoExitoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VideoExitoController extends Controller
{
    protected $videoService;

    public function __construct(VideoExitoService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * Obtener todos los videos para el admin
     */
    public function getVideos()
    {
        try {
            $videos = VideoExito::orderBy('orden')
                ->orderBy('id')
                ->get()
                ->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'titulo' => $video->titulo,
                        'subtitulo' => $video->subtitulo,
                        'descripcion' => $video->descripcion,
                        'url_youtube' => $video->url_youtube,
                        'youtube_id' => $video->youtube_id,
                        'thumbnail' => $video->thumbnail,
                        'nombre_persona' => $video->nombre_persona,
                        'cargo_persona' => $video->cargo_persona,
                        'orden' => $video->orden,
                        'activo' => $video->activo,
                        'created_at' => $video->created_at,
                        'updated_at' => $video->updated_at
                    ];
                });

            return response()->json($videos);
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo videos:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener videos'
            ], 500);
        }
    }

    /**
     * Obtener configuración
     */
    public function getConfig()
    {
        try {
            $config = $this->videoService->getConfiguracion();
            
            return response()->json($config);
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo configuración:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener configuración'
            ], 500);
        }
    }

    /**
     * Guardar configuración
     */
    public function saveConfig(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'subtitulo' => 'nullable|string|max:255',
                'videos_por_pagina' => 'required|integer|min:1|max:50',
                'mostrar_cargar_mas' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $config = VideoExitoConfig::first();
            
            if (!$config) {
                $config = new VideoExitoConfig();
            }

            $config->fill($request->all());
            $config->save();

            Log::info('Configuración de videos actualizada:', ['id' => $config->id]);

            return response()->json([
                'success' => true,
                'message' => 'Configuración guardada exitosamente',
                'data' => $config
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error guardando configuración:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar configuración: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo video
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'subtitulo' => 'nullable|string|max:255',
                'descripcion' => 'nullable|string',
                'url_youtube' => 'required|string|max:255',
                'youtube_id' => 'required|string|size:11',
                'nombre_persona' => 'required|string|max:255',
                'cargo_persona' => 'nullable|string|max:255',
                'orden' => 'required|integer|min:0',
                'activo' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $video = VideoExito::create($request->all());

            Log::info('Video creado:', ['id' => $video->id]);

            return response()->json([
                'success' => true,
                'message' => 'Video creado exitosamente',
                'data' => $video
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error creando video:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar video
     */
    public function update(Request $request, $id)
    {
        try {
            $video = VideoExito::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'subtitulo' => 'nullable|string|max:255',
                'descripcion' => 'nullable|string',
                'url_youtube' => 'required|string|max:255',
                'youtube_id' => 'required|string|size:11',
                'nombre_persona' => 'required|string|max:255',
                'cargo_persona' => 'nullable|string|max:255',
                'orden' => 'required|integer|min:0',
                'activo' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $video->update($request->all());

            Log::info('Video actualizado:', ['id' => $video->id]);

            return response()->json([
                'success' => true,
                'message' => 'Video actualizado exitosamente',
                'data' => $video
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error actualizando video:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar video
     */
    public function destroy($id)
    {
        try {
            $video = VideoExito::findOrFail($id);
            $video->delete();

            Log::info('Video eliminado:', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Video eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error eliminando video:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar estado del video (activar/desactivar)
     */
    public function toggleStatus($id)
    {
        try {
            $video = VideoExito::findOrFail($id);
            $video->activo = !$video->activo;
            $video->save();

            return response()->json([
                'success' => true,
                'activo' => $video->activo,
                'message' => $video->activo ? 'Video activado' : 'Video desactivado'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error cambiando estado:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado'
            ], 500);
        }
    }

    /**
     * Reordenar videos (opcional)
     */
    public function reorder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'orders' => 'required|array',
                'orders.*.id' => 'required|exists:videos_exito,id',
                'orders.*.orden' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            foreach ($request->orders as $item) {
                VideoExito::where('id', $item['id'])->update(['orden' => $item['orden']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error reordenando videos:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al reordenar videos'
            ], 500);
        }
    }
}
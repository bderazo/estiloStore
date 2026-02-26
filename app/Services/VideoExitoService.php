<?php

namespace App\Services;

use App\Models\VideoExito;
use App\Models\VideoExitoConfig;
use Illuminate\Support\Facades\Log;

class VideoExitoService
{
    /**
     * Obtener videos activos para la vista
     */
    public function getVideosActivos()
    {
        return VideoExito::activos()
            ->ordenados()
            ->get()
            ->map(function ($video) {
                return [
                    'id' => $video->id,
                    'titulo' => $video->titulo,
                    'subtitulo' => $video->subtitulo,
                    'descripcion' => $video->descripcion,
                    'nombre_persona' => $video->nombre_persona,
                    'cargo_persona' => $video->cargo_persona,
                    'thumbnail' => $video->thumbnail,
                    'embed_url' => $video->embed_url,
                    'watch_url' => $video->watch_url,
                    'youtube_id' => $video->youtube_id
                ];
            });
    }

    /**
     * Obtener videos paginados (para AJAX)
     */
    public function getVideosPaginados($page = 1, $perPage = 8)
    {
        return VideoExito::activos()
            ->ordenados()
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Obtener configuración
     */
    public function getConfiguracion()
    {
        try {
            $config = VideoExitoConfig::first();
            
            if (!$config) {
                $config = VideoExitoConfig::create([
                    'titulo' => 'Casos de Éxito',
                    'subtitulo' => 'Historias inspiradoras de nuestras clientas',
                    'videos_por_pagina' => 8,
                    'mostrar_cargar_mas' => true
                ]);
            }

            return $config;
            
        } catch (\Exception $e) {
            Log::error('Error al obtener configuración de videos:', [
                'error' => $e->getMessage()
            ]);
            
            return (object) [
                'titulo' => 'Casos de Éxito',
                'subtitulo' => 'Historias inspiradoras de nuestras clientas',
                'videos_por_pagina' => 8,
                'mostrar_cargar_mas' => true
            ];
        }
    }

    /**
     * Extraer ID de YouTube
     */
    public function extractYoutubeId($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }
}
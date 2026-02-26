<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\VideoExitoService;
use Illuminate\Http\Request;

class VideoExitoController extends Controller
{
    protected $videoService;

    public function __construct(VideoExitoService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * Mostrar página de casos de éxito
     */
    public function index()
    {
        $videos = $this->videoService->getVideosActivos();
        $config = $this->videoService->getConfiguracion();

        return view('web.videos-exito.index', compact('videos', 'config'));
    }

    /**
     * API para cargar más videos (AJAX)
     */
    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        $config = $this->videoService->getConfiguracion();
        
        $videos = $this->videoService->getVideosPaginados($page, $config->videos_por_pagina);

        return response()->json([
            'videos' => $videos->items(),
            'next_page' => $videos->hasMorePages() ? $videos->currentPage() + 1 : null,
            'has_more' => $videos->hasMorePages()
        ]);
    }
}
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCarouselResource;
use App\Models\Carrusel;
use App\Services\ArticuloService;
use App\Services\BannerService;
use App\Services\CategoriaService;
use App\Services\TestimonialService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(
        CategoriaService $categoriaService,
        ArticuloService $articuloService,
        BannerService $bannerService,
        TestimonialService $testimonialService
    ): View {
        $carruselActivos = Carrusel::query()
            ->where('estado', true)
            ->orderByDesc('created_at')
            ->get();

        $resource = ProductCarouselResource::collection($carruselActivos);
        $resolved = $resource->resolve();
        $carouselPayload = $resolved['data'] ?? $resolved;

        //categorias
        $categoriasMenu = $categoriaService->getMenuTienda();

        //ofertas
        $ofertas = $articuloService->getOfertasHome();

        //publicidades
        $publicidadUno = $bannerService->getBannerBySeccion('publicidad_uno');
        $publicidadDos = $bannerService->getBannerBySeccion('publicidad_dos');

        // 👇 NUEVO: Obtener testimonios
        $testimonios = $testimonialService->getTestimoniosActivos();
        $testimonialsConfig = $testimonialService->getConfiguracion();

        // 👇 AGREGAR VARIABLES DE DEPURACIÓN PARA EL NAVEGADOR
        $debugInfo = [
            'publicidadUno' => [
                'existe' => !is_null($publicidadUno),
                'tiene_imagen' => !is_null($publicidadUno) && !empty($publicidadUno->imagen_ruta),
                'id' => $publicidadUno->id ?? null,
                'titulo' => $publicidadUno->titulo ?? null,
                'imagen_ruta' => $publicidadUno->imagen_ruta ?? null,
            ],
            'publicidadDos' => [
                'existe' => !is_null($publicidadDos),
                'tiene_imagen' => !is_null($publicidadDos) && !empty($publicidadDos->imagen_ruta),
                'id' => $publicidadDos->id ?? null,
                'titulo' => $publicidadDos->titulo ?? null,
                'imagen_ruta' => $publicidadDos->imagen_ruta ?? null,
            ]
        ];

        return view('web.index', [
            'carouselItems' => collect($carouselPayload),
            'categoriasMenu' => $categoriasMenu,
            'ofertas' => $ofertas,
            'publicidadUno' => $publicidadUno,
            'publicidadDos' => $publicidadDos,
            'testimonios' => $testimonios,
            'testimonialsConfig' => $testimonialsConfig,
            'debugInfo' => $debugInfo, // 👈 Pasar a la vista
        ]);
    }
}
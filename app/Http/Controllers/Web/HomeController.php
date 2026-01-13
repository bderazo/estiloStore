<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCarouselResource;
use App\Models\Carrusel;
use App\Services\ArticuloService;
use App\Services\BannerService;
use App\Services\CategoriaService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(CategoriaService $categoriaService, ArticuloService $articuloService,BannerService $bannerService): View
    {
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

        return view('web.index', [
            'carouselItems'     => collect($carouselPayload),
            'categoriasMenu'    => $categoriasMenu,
            'ofertas'           => $ofertas,
            'publicidadUno'     => $publicidadUno,
            'publicidadDos'     => $publicidadDos,
        ]);
    }

}

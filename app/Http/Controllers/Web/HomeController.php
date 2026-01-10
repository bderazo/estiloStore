<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCarouselResource;
use App\Models\Carrusel;
use App\Services\ArticuloService;
use App\Services\BannerService;
use App\Services\CategoriaService;
use App\Services\FolletoService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(FolletoService $folletoService, CategoriaService $categoriaService, ArticuloService $articuloService): View
    {
        $carruselActivos = Carrusel::query()
            ->where('estado', true)
            ->orderByDesc('created_at')
            ->get();

        $resource = ProductCarouselResource::collection($carruselActivos);
        $resolved = $resource->resolve();
        $carouselPayload = $resolved['data'] ?? $resolved;        
        
        $folletoPrincipal=$folletoService->getFolletoPrincipal();

        //categorias
        $categoriasMenu = $categoriaService->getMenuTienda();

        //ofertas
        $ofertas = $articuloService->getOfertasHome();

        return view('web.index', [
            'carouselItems'     => collect($carouselPayload),
            'folletoPrincipal'  => $folletoPrincipal,
            'categoriasMenu'    => $categoriasMenu,
            'ofertas'           => $ofertas,
        ]);
    }

}

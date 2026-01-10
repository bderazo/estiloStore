<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ArticuloService;
use App\Services\CategoriaService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticuloDetalleController extends Controller
{
    public function __construct(
        private readonly ArticuloService $articuloService
    ) {}

    public function __invoke(string $slug, CategoriaService $categoriaService, ArticuloService $articuloService)
    {
        $articulo = $this->articuloService->getDetalleArticulo($slug);
        $categoriasMenu = $categoriaService->getMenuTienda();

        $imagen=$this->articuloService->getImagenPrincipalAttribute();
        // Procesar imagen
        $articulo->imagen_full = $this->resolveImageUrl($imagen);
        $articulo->imagen_full = $imagen;

        return view('web.articulos.detalle', [
            'articulo' => $articulo,
            'categoriasMenu' => $categoriasMenu
        ]);
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (!$path) return asset('assets/img/no-product.png');
        if (Str::startsWith($path, ['http', '//'])) return $path;
        return Storage::disk('public')->exists($path) ? Storage::url($path) : asset($path);
    }
}
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Nosotros;
use App\Services\BannerService;
use App\Services\CategoriaService;
use App\Services\EmpresaService;
use App\Services\FolletoService;
use App\Services\NosotrosService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NosotrosController extends Controller
{
    public function __construct(private readonly NosotrosService $nosotrosService)
    {
    }

    public function __invoke(FolletoService $folletoService, BannerService $bannerService, CategoriaService $categoriaService, EmpresaService $empresaService)
    {
        // 1. Obtenemos las secciones dinámicas (Quienes somos, misión, visión, etc.)
        $seccionesEmpresa = $empresaService->getDatosNosotros();
        // 2. Procesamos las imágenes de cada sección dinámicamente
        foreach ($seccionesEmpresa as $seccion) {
            $seccion->url_completa = $this->resolveImageUrl($seccion->imagen);
        }

        $nosotros = $this->nosotrosService->getSingleton() ?? new Nosotros();
        $folletoPrincipal=$folletoService->getFolletoPrincipal();
        //categorias
        $categoriasMenu = $categoriaService->getMenuTienda();
        
        $bannerNosotrosHeader = $bannerService->getBannerBySeccion('nosotros_header');


        $imagenes = [
            'portada' => $this->resolveImageUrl($nosotros->imagen_portada_url ?? null),
            'cuerpo_1' => $this->resolveImageUrl($nosotros->imagen_cuerpo_1_url ?? null),
            'cuerpo_2' => $this->resolveImageUrl($nosotros->imagen_cuerpo_2_url ?? null),
        ];

        return view('web.nosotros', [
            'nosotros'                  => $nosotros,
            'imagenes'                  => $imagenes,
            'folletoPrincipal'          => $folletoPrincipal,
            'bannerNosotrosHeader'      => $bannerNosotrosHeader,
            'categoriasMenu'            => $categoriasMenu,
            'secciones'                 => $seccionesEmpresa,
        ]);
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset($path);
    }
}

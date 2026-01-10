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

class CategoriaController extends Controller
{
    protected $categoriaService;
    protected $folletoService;
    protected $bannerService;
    protected $empresaService;
    protected $nosotrosService;

    public function __construct(FolletoService $folletoService, BannerService $bannerService, CategoriaService $categoriaService, EmpresaService $empresaService, NosotrosService $nosotrosService) {
        $this->categoriaService = $categoriaService;
        $this->bannerService    = $bannerService;
        $this->folletoService   = $folletoService;
        $this->empresaService   = $empresaService;
        $this->nosotrosService  = $nosotrosService;
    }


public function show($slug) 
{
    $data = $this->categoriaService->getArticulosPorCategoria($slug);
    $categoriasMenu = $this->categoriaService->getMenuTienda();
    
    // Usamos la imagen de la categoría como fondo del banner
    $bannerPage = $data['categoria']->imagen 
                  ? asset('storage/'.$data['categoria']->imagen) 
                  : asset('web/assets/img/other/breadcrumb-bg.jpg');

    return view('web.tienda.categoria', [
        'categoria'      => $data['categoria'],
        'articulos'      => $data['articulos'], // Aquí vienen los productos
        'categoriasMenu' => $categoriasMenu,
        'bannerPage'     => $bannerPage
    ]);
}




  public function index()
{
    // 1. Datos para el Header y Layout (Lo que ya tenías)
    $categoriasMenu = $this->categoriaService->getMenuTienda();
    $folletoPrincipal = $this->folletoService->getFolletoPrincipal();

    // 2. Banner específico para la página de categorías
    $bannerCategorias = $this->bannerService->getBannerBySeccion('banner_categorias');

    // 3. OBTENER TODAS LAS CATEGORÍAS (Esta es la clave)
    // Asumo que tu servicio tiene un método para traer todas las categorías con conteo
    $todasLasCategorias = $this->categoriaService->getAllActiveWithCount(); 

    return view('web.categorias', [
        'categoriasMenu'   => $categoriasMenu,
        'bannerCategorias' => $bannerCategorias,
        'folletoPrincipal' => $folletoPrincipal,
        'categorias'       => $todasLasCategorias, // Pasamos las categorías a la vista
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
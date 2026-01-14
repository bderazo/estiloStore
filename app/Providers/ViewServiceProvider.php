<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\BannerService;
use App\Services\CategoriaService;
use App\Services\FolletoService;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // BANNERS
        View::composer('*', function ($view) {
            // Definimos el tiempo de caché: 0 para local (desactivado), 600 para producción
            $cacheTime = app()->environment('local') ? 0 : 600;

            // Banner header
            $bannerHeader = Cache::remember('banner_header', $cacheTime, function () {
                return app(BannerService::class)->getBannerBySeccion('initial_header');
            });

            // Categorías menú
            $categoriasMenu = Cache::remember('categorias_menu', $cacheTime, function () {
                return app(CategoriaService::class)->getMenuTienda();
            });

            // Folleto principal
            $folletoPrincipal = Cache::remember('folleto_principal', $cacheTime, function () {
                return app(FolletoService::class)->getFolletoPrincipal();
            });

            // Categorías padre
            $categoriasGlobal = Cache::remember('categorias_global', $cacheTime, function () {
                return app(CategoriaService::class)->getCategoriasPadre();
            });

            // Pasar datos a todas las vistas
            $view->with([
                'bannerHeader'     => $bannerHeader,
                'categoriasMenu'   => $categoriasMenu,
                'folletoPrincipal' => $folletoPrincipal,
                'categoriasGlobal' => $categoriasGlobal,
            ]);
        });
    }
}

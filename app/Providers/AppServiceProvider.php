<?php

namespace App\Providers;

use App\Services\BannerService;
use App\Services\CategoriaService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $bannerService = app(BannerService::class);
        $bannerHeader = $bannerService->getBannerBySeccion('initial_header');
        View::share('bannerHeader', $bannerHeader);

        
        $categoriaService = app(CategoriaService::class);
        $categoriasMenu = $categoriaService->getMenuTienda();
        View::share('categoriasMenu', $categoriasMenu);

    }
}

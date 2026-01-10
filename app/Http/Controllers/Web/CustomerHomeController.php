<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Services\CategoriaService;
use App\Services\BannerService;
use Illuminate\Http\Request;

class CustomerHomeController extends Controller
{
    public function __construct(
        private readonly CustomerService $customerService,
        private readonly CategoriaService $categoriaService
    ) {}

    /**
     * Vista principal del Dashboard del Cliente
     */
    public function __invoke(BannerService $bannerService)
    {
        $user = auth()->user();
        
        // Data del Header/Menú
        $categoriasMenu = $this->categoriaService->getMenuTienda();
        
        // Data del Cliente
        $stats = $this->customerService->getDashboardStats($user);
        $ordenesPendientes = $this->customerService->getOrdenesPendientes($user);
        $historialOrdenes = $user->orders()->orderBy('created_at', 'desc')->paginate(10);
        $puntosLog = $this->customerService->getPuntosHistorial($user);

        return view('web.customer.dashboard', [
            'user'              => $user,
            'stats'             => $stats,
            'ordenesPendientes' => $ordenesPendientes,
            'historial'         => $historialOrdenes,
            'puntosLog'         => $puntosLog,
            'categoriasMenu'    => $categoriasMenu,
        ]);
    }
}
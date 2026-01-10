<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCarouselResource;
use App\Models\Carrusel;

use App\Services\FolletoService;
use Illuminate\Contracts\View\View;

class FolletoController extends Controller
{
    protected $folletoService;
    public function __construct(FolletoService $folletoService)
    {
        $this->folletoService=$folletoService;
    }
    public function descargar($id, FolletoService $service)
    {
        try {
            $path = $service->procesarDescarga($id);
            return response()->download($path);
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo descargar el folleto.');
        }
    }
}

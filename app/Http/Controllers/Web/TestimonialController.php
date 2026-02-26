<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\TestimonialService;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    protected $testimonialService;

    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    public function index()
    {
        $testimonios = $this->testimonialService->getTestimoniosActivos();
        $config = $this->testimonialService->getConfiguracion();

        return response()->json([
            'testimonios' => $testimonios,
            'config' => $config
        ]);
    }
}
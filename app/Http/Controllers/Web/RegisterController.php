<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\BannerService;
use App\Services\CategoriaService;
use App\Services\LegalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * Muestra el formulario de registro y procesa la data
     */
    public function __invoke(
        Request $request, 
        CategoriaService $categoriaService, 
        BannerService $bannerService,
        LegalService $legalService
    ) {
        // Si la petición es POST, procesamos el registro
        if ($request->isMethod('post')) {
            return $this->store($request);
        }
        // Si es GET, mostramos la vista con los datos necesarios
        $categoriasMenu = $categoriaService->getMenuTienda();
        $bannerPage     = $bannerService->getBannerBySeccion('registro_header');

        return view('web.register', [
            'categoriasMenu' => $categoriasMenu,
            'legalService'   => $legalService // Para los links de términos y condiciones
        ]);
    }

    private function store(Request $request)
    {
        $validated = $request->validate([
            'nombres'             => 'required|string|max:255',
            'apellidos'           => 'required|string|max:255',
            'ciudad_provincia'    => 'required|string|max:255',
            'whatsapp'            => 'required|string|min:9',
            'email'               => 'required|email|unique:users,email',
            'numero_documento' => 'nullable|string|unique:users,numero_documento',
            'tipo_documento'      => 'nullable|string', // Se puede ocultar o fijar en el service
            'referido_por_codigo' => 'nullable|exists:users,codigo_referido',
            'password'            => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = $this->authService->registerUser($validated);
            auth()->login($user);
            return redirect()->route('home')->with('success', '¡Bienvenida a la comunidad!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (!$path) return null;

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset($path);
    }
}
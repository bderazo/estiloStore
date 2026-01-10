<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ArticuloService;
use App\Services\AuthService;
use App\Services\BannerService;
use App\Services\CategoriaService;
use App\Services\FolletoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,        
    ) {}

    public function showLoginForm(CategoriaService $categoriaService, BannerService $bannerService)
    {
        return view('web.login', [
            'categoriasMenu' => $categoriaService->getMenuTienda(),
            'bannerHeader'     => $bannerService->getBannerBySeccion('initial_header'),
            'bannerPage'     => $bannerService->getBannerBySeccion('login_header')
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if ($this->authService->login($credentials, $remember)) {
            return redirect()->intended(route('customer.dashboard'))
                ->with('success', '¡Qué alegría verte de nuevo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
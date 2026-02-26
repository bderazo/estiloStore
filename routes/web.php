<?php

use App\Http\Controllers\Web\CarritoController;
use App\Http\Controllers\Web\CategoriaController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Web\ArticuloDetalleController;
use App\Http\Controllers\Web\BusquedaController;
use App\Http\Controllers\Web\CustomerHomeController;
use App\Http\Controllers\Web\FolletoController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\NosotrosController;
use App\Http\Controllers\Web\OrderPaymentController;
use App\Http\Controllers\Web\PagoController;
use App\Http\Controllers\Web\PedidoController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\TestimonialController;
use App\Http\Controllers\Web\VideoExitoController;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Ruta para optimizar la aplicación para producción
 * Ejecuta múltiples comandos de Artisan para mejorar el rendimiento
 */
Route::get('/optimize-production', function () {
    try {
        $commands = [];
        $results = [];

        // Limpiar caché de configuración
        Artisan::call('config:clear');
        $results[] = '✅ Config cache cleared';

        // Limpiar caché de vistas
        Artisan::call('view:clear');
        $results[] = '✅ View cache cleared';

        // Limpiar caché de rutas
        Artisan::call('route:clear');
        $results[] = '✅ Route cache cleared';

        // Crear enlace simbólico de storage
        Artisan::call('storage:link');
        $results[] = '✅ Storage symlink created';

        // Cachear configuración para producción
        Artisan::call('config:cache');
        $results[] = '✅ Configuration cached';

        // Cachear rutas para producción
        Artisan::call('route:cache');
        $results[] = '✅ Routes cached';

        // Cachear vistas para producción
        Artisan::call('view:cache');
        $results[] = '✅ Views cached';

        // Optimizar autoloader
        Artisan::call('optimize');
        $results[] = '✅ Application optimized';

        return response()->json([
            'success' => true,
            'message' => 'Aplicación optimizada correctamente para producción',
            'commands_executed' => $results,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al optimizar la aplicación',
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
})->name('optimize.production');

/**
 * Ruta para limpiar todos los cachés (útil en desarrollo)
 */
Route::get('/clear-cache', function () {
    try {
        $results = [];

        // Limpiar caché de aplicación
        Artisan::call('cache:clear');
        $results[] = '✅ Application cache cleared';

        // Limpiar caché de configuración
        Artisan::call('config:clear');
        $results[] = '✅ Config cache cleared';

        // Limpiar caché de vistas
        Artisan::call('view:clear');
        $results[] = '✅ View cache cleared';

        // Limpiar caché de rutas
        Artisan::call('route:clear');
        $results[] = '✅ Route cache cleared';

        return response()->json([
            'success' => true,
            'message' => 'Todos los cachés limpiados correctamente',
            'commands_executed' => $results,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al limpiar los cachés',
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
})->name('cache.clear');

/**
 * Ruta pública - Página principal del sitio web
 */
Route::get('/', HomeController::class)->name('home');
Route::get('/folleto/descargar/{id}', [FolletoController::class, 'descargar'])->name('folleto.descargar');

Route::get('/nosotros', NosotrosController::class)->name('nosotros');


/**
 * Rutas del panel de administración (SPA Vue)
 * Solo las URLs que comiencen con /administrador cargarán la aplicación Vue
 */
Route::get('/administrador/{any?}', [AppController::class, 'index'])->where('any', '.*');


//ruta de categorias
Route::get('/categoria/{slug}', [CategoriaController::class, 'show'])->name('tienda.categoria');
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');

// Rutas de Registro
Route::get('/registro', RegisterController::class)->name('register');
Route::post('/registro', RegisterController::class)->name('register.store');
/*
Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/registro', [RegisterController::class, 'register'])->name('register.store');*/


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('carrito')->name('web.carrito.')->group(function () {

    Route::get('/', [CarritoController::class, 'index'])->name('index');
    Route::post('/agregar', [CarritoController::class, 'agregar'])->name('add');
    Route::get('/buscar', [CarritoController::class, 'buscar'])->name('buscar');
    Route::post('/add', [CarritoController::class, 'agregar'])->name('add');
    //Route::delete('/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('eliminar');
    Route::post('/remove', [CarritoController::class, 'eliminar'])->name('remove'); // LA QUE FALTA
    Route::post('/pagar', [CarritoController::class, 'pagar'])->name('pagar');
    Route::post('/checkout', [CarritoController::class, 'procesarReserva'])->name('checkout'); // PROCESAR PAGO

});

Route::prefix('mis-reservas')->name('web.pedidos.')->group(function () {
    Route::get('/', [PedidoController::class, 'index'])->name('index');
    Route::get('/{id}', [PedidoController::class, 'show'])->name('show');
    Route::post('/{id}/subir-pago', [PedidoController::class, 'subirPago'])->name('subir_pago');
});
Route::post('/pedidos/{id}/asignar-transporte', [PedidoController::class, 'asignarTransporte'])->name('web.pedidos.asignar_transporte');

Route::group(['middleware' => ['auth']], function () {
    // Home del Cliente
    Route::get('/mi-cuenta', CustomerHomeController::class)->name('customer.dashboard');
    // Ruta para subir el Voucher
    Route::post('/order/{order}/upload-payment', [OrderPaymentController::class, 'upload'])
        ->name('customer.upload.payment');

});

Route::get('/articulo/{slug}', ArticuloDetalleController::class)->name('web.articulo.detalle');

// --- RUTAS DE PAGO (Requieren estar logueado) ---


// Rutas de Pago
Route::get('/pago', [PagoController::class, 'index'])->name('web.pago.index'); // PUBLICA

Route::middleware(['auth'])->group(function () {
    // Solo el proceso de guardar requiere sesión
    Route::post('/pago/confirmar', [PagoController::class, 'confirmarPedido'])->name('web.pago.confirmar');
});

// Ruta adicional para el éxito de la compra
Route::get('/pedido-finalizado/{id}', [PagoController::class, 'exito'])->name('web.pago.exito');

// Ruta para el buscador global
Route::get('/buscar', [BusquedaController::class, 'buscar'])->name('web.buscar');

Route::get('/testimonios', [TestimonialController::class, 'index']);

Route::get('/casos-de-exito', [VideoExitoController::class, 'index'])->name('videos.exito');
Route::get('/casos-de-exito/cargar-mas', [VideoExitoController::class, 'loadMore'])->name('videos.exito.load-more');

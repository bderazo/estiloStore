<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\ModulesController;
use App\Http\Controllers\API\CarruselController;
use App\Http\Controllers\API\EmpresaDatoController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\CategoriaController;
use App\Http\Controllers\API\MarcaController;
use App\Http\Controllers\API\FolletoController;
use App\Http\Controllers\API\MetodoPagoController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\TallaController;
use App\Http\Controllers\API\PlazaController;
use App\Http\Controllers\API\MedidaController;
use App\Http\Controllers\API\NosotrosController;
use App\Http\Controllers\API\SaborController;
use App\Http\Controllers\API\ModeloController;
use App\Http\Controllers\API\TonoController;
use App\Http\Controllers\API\ArticuloController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas de autenticación
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.auth');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt.auth');
    Route::get('me', [AuthController::class, 'me'])->middleware('jwt.auth');
});

// Rutas protegidas con autenticación JWT
Route::middleware(['jwt.auth'])->group(function () {

    // Rutas de selects (requieren autenticación pero sin permisos específicos)
    Route::get('/categorias/for-select', [CategoriaController::class, 'getForSelect']);
    Route::get('/marcas/for-select', [MarcaController::class, 'getForSelect']);
    Route::get('/colores/for-select', [ColorController::class, 'getForSelect']);
    Route::get('/tallas/for-select', [TallaController::class, 'getForSelect']);
    Route::get('/plazas/for-select', [PlazaController::class, 'getForSelect']);
    Route::get('/medidas/for-select', [MedidaController::class, 'getForSelect']);
    Route::get('/sabores/for-select', [SaborController::class, 'getForSelect']);
    Route::get('/modelos/for-select', [ModeloController::class, 'getForSelect']);
    Route::get('/tonos/for-select', [TonoController::class, 'getForSelect']);

    // Rutas de usuarios
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersController::class, 'index'])->middleware('permission:usuarios.index');
        Route::post('/', [UsersController::class, 'store'])->middleware('permission:usuarios.create');
        Route::get('/{user}', [UsersController::class, 'show'])->middleware('permission:usuarios.show');
        Route::put('/{user}', [UsersController::class, 'update'])->middleware('permission:usuarios.edit');
        Route::delete('/{user}', [UsersController::class, 'destroy'])->middleware('permission:usuarios.delete');
    });

    // Rutas de roles
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RolesController::class, 'index'])->middleware('permission:roles.index');
        Route::post('/', [RolesController::class, 'store'])->middleware('permission:roles.create');
        Route::get('/{role}', [RolesController::class, 'show'])->middleware('permission:roles.show');
        Route::put('/{role}', [RolesController::class, 'update'])->middleware('permission:roles.edit');
        Route::delete('/{role}', [RolesController::class, 'destroy'])->middleware('permission:roles.delete');

        // Rutas especiales para roles
        Route::get('/all/list', [RolesController::class, 'all']); // Todos los roles sin paginación para selects
        Route::get('/permissions/by-module', [RolesController::class, 'getPermissionsByModule'])->middleware('permission:roles.assign');
        Route::post('/{role}/assign-permissions', [RolesController::class, 'assignPermissions'])->middleware('permission:roles.assign');
    });

    // Rutas de módulos
    Route::group(['prefix' => 'modules'], function () {
        Route::get('/all', [ModulesController::class, 'all']); // Todos los módulos con permisos sin paginación
        Route::get('/', [ModulesController::class, 'index'])->middleware('permission:modulos.index');
        Route::post('/', [ModulesController::class, 'store'])->middleware('permission:modulos.create');
        Route::get('/{module}', [ModulesController::class, 'show'])->middleware('permission:modulos.show');
        Route::put('/{module}', [ModulesController::class, 'update'])->middleware('permission:modulos.edit');
        Route::delete('/{module}', [ModulesController::class, 'destroy'])->middleware('permission:modulos.delete');
    });

    // Rutas de carrusel
    Route::group(['prefix' => 'carrusel'], function () {
        Route::get('/', [CarruselController::class, 'index'])->middleware('permission:carrusel.index');
        Route::post('/', [CarruselController::class, 'store'])->middleware('permission:carrusel.create');
        Route::get('/posiciones', [CarruselController::class, 'getPosiciones']); // Para obtener las opciones del select
        Route::get('/{carrusel}', [CarruselController::class, 'show'])->middleware('permission:carrusel.show');
        Route::post('/{carrusel}', [CarruselController::class, 'update'])->middleware('permission:carrusel.edit'); // POST para manejar archivos
        Route::put('/{carrusel}', [CarruselController::class, 'update'])->middleware('permission:carrusel.edit');
        Route::delete('/{carrusel}', [CarruselController::class, 'destroy'])->middleware('permission:carrusel.delete');
        Route::patch('/{carrusel}/toggle-estado', [CarruselController::class, 'toggleEstado'])->middleware('permission:carrusel.estado');
    });

    // Rutas de banners
    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', [BannerController::class, 'index']);//->middleware('permission:banners.index');
        Route::post('/', [BannerController::class, 'store']);//->middleware('permission:banners.create');
        Route::get('/secciones', [BannerController::class, 'getSecciones']);//->middleware('permission:banners.create');
        Route::get('/{banner}', [BannerController::class, 'show']);//->middleware('permission:banners.show');
        Route::post('/{banner}', [BannerController::class, 'update']);//->middleware('permission:banners.edit'); // POST para manejar archivos
        Route::put('/{banner}', [BannerController::class, 'update']);//->middleware('permission:banners.edit');
        Route::delete('/{banner}', [BannerController::class, 'destroy']);//->middleware('permission:banners.delete');
        Route::patch('/{banner}/toggle-estado', [BannerController::class, 'toggleEstado']);//->middleware('permission:banners.estado');
    });

    // Rutas de datos de la empresa
    Route::group(['prefix' => 'empresa-datos'], function () {
        Route::get('/', [EmpresaDatoController::class, 'index']);//->middleware('permission:empresa-datos.index');
        Route::post('/', [EmpresaDatoController::class, 'store']);//->middleware('permission:empresa-datos.create');
        Route::get('/claves', [EmpresaDatoController::class, 'getClaves']);//->middleware('permission:empresa-datos.create');
        Route::get('/{empresa_dato}', [EmpresaDatoController::class, 'show']);//->middleware('permission:empresa-datos.show');
        Route::post('/{empresa_dato}', [EmpresaDatoController::class, 'update']);//->middleware('permission:empresa-datos.edit');
        Route::put('/{empresa_dato}', [EmpresaDatoController::class, 'update']);//->middleware('permission:empresa-datos.edit');
        Route::delete('/{empresa_dato}', [EmpresaDatoController::class, 'destroy']);//->middleware('permission:empresa-datos.delete');
        Route::patch('/{empresa_dato}/toggle-activo', [EmpresaDatoController::class, 'toggleActivo']);//->middleware('permission:empresa-datos.estado');
    });

    // Rutas públicas
    Route::get('/folletos/descargar/{id}', [FolletoController::class, 'descargar'])
        ->name('folletos.descargar.publico');

    Route::get('/folletos/estadisticas', [FolletoController::class, 'estadisticas'])
        ->name('folletos.estadisticas.publico');

    // Rutas protegidas (requieren autenticación)
        Route::prefix('folletos')->group(function () {
            Route::get('/', [FolletoController::class, 'index']);
                // ->middleware('can:folletos.index')
                // ->name('folletos.index');
            
            Route::get('/{folleto}', [FolletoController::class, 'show'])
                // ->middleware('can:folletos.view')
                ->name('folletos.show');
            
            Route::post('/', [FolletoController::class, 'store'])
                // ->middleware('can:folletos.create')
                ->name('folletos.store');
            
            Route::put('/{folleto}', [FolletoController::class, 'update'])
                // ->middleware('can:folletos.edit')
                ->name('folletos.update');
            
            Route::delete('/{folleto}', [FolletoController::class, 'destroy'])
                // ->middleware('can:folletos.delete')
                ->name('folletos.destroy');
            
            Route::patch('/{folleto}/toggle-estado', [FolletoController::class, 'toggleEstado'])
                // ->middleware('can:folletos.edit')
                ->name('folletos.toggle-estado');
            
            Route::post('/{folleto}/registrar-descarga', [FolletoController::class, 'incrementarDescargas'])
                // ->middleware('can:folletos.view')
                ->name('folletos.registrar-descarga');
        });
    
        // Ruta para métodos de pago

            Route::apiResource('metodos-pago', MetodoPagoController::class);
            
            Route::patch('metodos-pago/{metodoPago}/toggle-activo', 
                [MetodoPagoController::class, 'toggleActivo']);
            
            Route::post('metodos-pago/ordenar', 
                [MetodoPagoController::class, 'ordenar']);
        
        Route::get('metodos-pago-activos', 
            [MetodoPagoController::class, 'activos']);

    Route::get('metodos-pago-publicos', 
        [MetodoPagoController::class, 'activos']);

    // Rutas de categorías
    Route::group(['prefix' => 'categorias'], function () {
        Route::get('/', [CategoriaController::class, 'index'])->middleware('permission:categorias.index');
        Route::post('/', [CategoriaController::class, 'store'])->middleware('permission:categorias.create');
        //Route::get('/for-select', [CategoriaController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::post('/reorder', [CategoriaController::class, 'reorder'])->middleware('permission:categorias.edit'); // Para reordenar
        Route::get('/{categoria}', [CategoriaController::class, 'show'])->middleware('permission:categorias.show');
        Route::put('/{categoria}', [CategoriaController::class, 'update'])->middleware('permission:categorias.edit');
        Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->middleware('permission:categorias.delete');
        Route::patch('/{categoria}/toggle-activo', [CategoriaController::class, 'toggleActivo'])->middleware('permission:categorias.estado');
    });

    // Rutas de marcas
    Route::group(['prefix' => 'marcas'], function () {
        Route::get('/', [MarcaController::class, 'index'])->middleware('permission:marcas.index');
        Route::post('/', [MarcaController::class, 'store'])->middleware('permission:marcas.create');
        //Route::get('/for-select', [MarcaController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{marca}', [MarcaController::class, 'show'])->middleware('permission:marcas.show');
        Route::put('/{marca}', [MarcaController::class, 'update'])->middleware('permission:marcas.edit');
        Route::delete('/{marca}', [MarcaController::class, 'destroy'])->middleware('permission:marcas.delete');
        Route::patch('/{marca}/toggle-activo', [MarcaController::class, 'toggleActivo'])->middleware('permission:marcas.estado');
    });

    // Rutas de colores
    Route::group(['prefix' => 'colores'], function () {
        Route::get('/', [ColorController::class, 'index'])->middleware('permission:colores.index');
        Route::post('/', [ColorController::class, 'store'])->middleware('permission:colores.create');
        //Route::get('/for-select', [ColorController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{color}', [ColorController::class, 'show'])->middleware('permission:colores.show');
        Route::put('/{color}', [ColorController::class, 'update'])->middleware('permission:colores.edit');
        Route::delete('/{color}', [ColorController::class, 'destroy'])->middleware('permission:colores.delete');
        Route::patch('/{color}/toggle-activo', [ColorController::class, 'toggleActivo'])->middleware('permission:colores.estado');
    });

    // Rutas de tallas
    Route::group(['prefix' => 'tallas'], function () {
        Route::get('/', [TallaController::class, 'index'])->middleware('permission:tallas.index');
        Route::post('/', [TallaController::class, 'store'])->middleware('permission:tallas.create');
        //Route::get('/for-select', [TallaController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{talla}', [TallaController::class, 'show'])->middleware('permission:tallas.show');
        Route::put('/{talla}', [TallaController::class, 'update'])->middleware('permission:tallas.edit');
        Route::delete('/{talla}', [TallaController::class, 'destroy'])->middleware('permission:tallas.delete');
        Route::patch('/{talla}/toggle-activo', [TallaController::class, 'toggleActivo'])->middleware('permission:tallas.estado');
    });

    // Rutas de plazas
    Route::group(['prefix' => 'plazas'], function () {
        Route::get('/', [PlazaController::class, 'index'])->middleware('permission:plazas.index');
        Route::post('/', [PlazaController::class, 'store'])->middleware('permission:plazas.create');
        //Route::get('/for-select', [PlazaController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{plaza}', [PlazaController::class, 'show'])->middleware('permission:plazas.show');
        Route::put('/{plaza}', [PlazaController::class, 'update'])->middleware('permission:plazas.edit');
        Route::delete('/{plaza}', [PlazaController::class, 'destroy'])->middleware('permission:plazas.delete');
        Route::patch('/{plaza}/toggle-activo', [PlazaController::class, 'toggleActivo'])->middleware('permission:plazas.estado');
    });

    // Ruta singleton de Nosotros
    Route::group(['prefix' => 'nosotros'], function () {
        Route::get('/', [NosotrosController::class, 'show'])->middleware('permission:view nosotros');
        Route::post('/', [NosotrosController::class, 'store'])->middleware('permission:manage nosotros');
        Route::put('/', [NosotrosController::class, 'update'])->middleware('permission:manage nosotros');
    });

    // Rutas de medidas
    Route::group(['prefix' => 'medidas'], function () {
        Route::get('/', [MedidaController::class, 'index'])->middleware('permission:medidas.index');
        Route::post('/', [MedidaController::class, 'store'])->middleware('permission:medidas.create');
        //Route::get('/for-select', [MedidaController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{medida}', [MedidaController::class, 'show'])->middleware('permission:medidas.show');
        Route::put('/{medida}', [MedidaController::class, 'update'])->middleware('permission:medidas.edit');
        Route::delete('/{medida}', [MedidaController::class, 'destroy'])->middleware('permission:medidas.delete');
        Route::patch('/{medida}/toggle-activo', [MedidaController::class, 'toggleActivo'])->middleware('permission:medidas.estado');
    });

    // Rutas de sabores
    Route::group(['prefix' => 'sabores'], function () {
        Route::get('/', [SaborController::class, 'index'])->middleware('permission:sabores.index');
        Route::post('/', [SaborController::class, 'store'])->middleware('permission:sabores.create');
        //Route::get('/for-select', [SaborController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{sabor}', [SaborController::class, 'show'])->middleware('permission:sabores.show');
        Route::put('/{sabor}', [SaborController::class, 'update'])->middleware('permission:sabores.edit');
        Route::delete('/{sabor}', [SaborController::class, 'destroy'])->middleware('permission:sabores.delete');
        Route::patch('/{sabor}/toggle-activo', [SaborController::class, 'toggleActivo'])->middleware('permission:sabores.estado');
    });

    // Rutas de modelos
    Route::group(['prefix' => 'modelos'], function () {
        Route::get('/', [ModeloController::class, 'index'])->middleware('permission:modelos.index');
        Route::post('/', [ModeloController::class, 'store'])->middleware('permission:modelos.create');
        //Route::get('/for-select', [ModeloController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{modelo}', [ModeloController::class, 'show'])->middleware('permission:modelos.show');
        Route::put('/{modelo}', [ModeloController::class, 'update'])->middleware('permission:modelos.edit');
        Route::delete('/{modelo}', [ModeloController::class, 'destroy'])->middleware('permission:modelos.delete');
        Route::patch('/{modelo}/toggle-activo', [ModeloController::class, 'toggleActivo'])->middleware('permission:modelos.estado');
    });

    // Rutas de tonos
    Route::group(['prefix' => 'tonos'], function () {
        Route::get('/', [TonoController::class, 'index'])->middleware('permission:tonos.index');
        Route::post('/', [TonoController::class, 'store'])->middleware('permission:tonos.create');
        //Route::get('/for-select', [TonoController::class, 'getForSelect']); // Sin permisos, solo autenticación
        Route::get('/{tono}', [TonoController::class, 'show'])->middleware('permission:tonos.show');
        Route::put('/{tono}', [TonoController::class, 'update'])->middleware('permission:tonos.edit');
        Route::delete('/{tono}', [TonoController::class, 'destroy'])->middleware('permission:tonos.delete');
        Route::patch('/{tono}/toggle-activo', [TonoController::class, 'toggleActivo'])->middleware('permission:tonos.estado');
    });

    // Rutas de artículos
    Route::group(['prefix' => 'articulos'], function () {
        Route::get('/', [ArticuloController::class, 'index'])->middleware('permission:articulos.index');
        Route::post('/', [ArticuloController::class, 'store'])->middleware('permission:articulos.create');
        Route::get('/{articulo}', [ArticuloController::class, 'show'])->middleware('permission:articulos.show');
        Route::put('/{articulo}', [ArticuloController::class, 'update'])->middleware('permission:articulos.edit');
        Route::delete('/{articulo}', [ArticuloController::class, 'destroy'])->middleware('permission:articulos.delete');
        Route::patch('/{articulo}/toggle-activo', [ArticuloController::class, 'toggleActivo'])->middleware('permission:articulos.estado');
        Route::patch('/{articulo}/toggle-destacado', [ArticuloController::class, 'toggleDestacado'])->middleware('permission:articulos.estado');

        // Rutas de variantes
        Route::group(['prefix' => '{articulo}/variantes'], function () {
            Route::post('/', [ArticuloController::class, 'storeVariante'])->middleware('permission:articulos.crear_variantes');
            Route::put('/{variante}', [ArticuloController::class, 'updateVariante'])->middleware('permission:articulos.editar_variantes');
            Route::delete('/{variante}', [ArticuloController::class, 'destroyVariante'])->middleware('permission:articulos.eliminar_variantes');
        });

        // Rutas de stock
        Route::group(['prefix' => 'variantes/{variante}/stock'], function () {
            Route::get('/', [ArticuloController::class, 'infoStock'])->middleware('permission:articulos.ver_stock');
            Route::post('/reservar', [ArticuloController::class, 'reservarStock'])->middleware('permission:articulos.reservar_stock');
            Route::post('/liberar', [ArticuloController::class, 'liberarStock'])->middleware('permission:articulos.liberar_stock');
            Route::post('/decrementar', [ArticuloController::class, 'decrementarStock'])->middleware('permission:articulos.decrementar_stock');
        });
    });

    // Ruta de inicio/dashboard
    Route::get('/dashboard', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Bienvenido al sistema',
            'data' => [
                'user' => $request->user()->nombre_completo,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ],
            'errors' => null
        ]);
    })->middleware('permission:inicio.view');

});

<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuloPermisosSeed extends Seeder
{

    public function run(): void
    {
        // Crear rol Super Administrador si no existe
        $roleSuperAdmin = Role::first();

        // Crear módulos
        $moduloUsuarios = Module::create([
            'slug' => 'usuarios',
            'nombre' => 'Usuarios',
            'descripcion' => 'Gestión de usuarios del sistema'
        ]);

        $moduloRoles = Module::create([
            'slug' => 'roles',
            'nombre' => 'Roles',
            'descripcion' => 'Gestión de roles y permisos'
        ]);

        $moduloSistema = Module::create([
            'slug' => 'sistema',
            'nombre' => 'Sistema',
            'descripcion' => 'Configuraciones del sistema'
        ]);

        // Crear permisos para usuarios
        $permisosUsuarios = [
            ['slug' => 'usuarios.index', 'nombre' => 'Ver listado de usuarios'],
            ['slug' => 'usuarios.show', 'nombre' => 'Ver detalles de usuario'],
            ['slug' => 'usuarios.create', 'nombre' => 'Crear usuarios'],
            ['slug' => 'usuarios.edit', 'nombre' => 'Editar usuarios'],
            ['slug' => 'usuarios.delete', 'nombre' => 'Eliminar usuarios'],
        ];

        foreach ($permisosUsuarios as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $moduloUsuarios->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        // Crear permisos para roles
        $permisosRoles = [
            ['slug' => 'roles.index', 'nombre' => 'Ver listado de roles'],
            ['slug' => 'roles.show', 'nombre' => 'Ver detalles de rol'],
            ['slug' => 'roles.create', 'nombre' => 'Crear roles'],
            ['slug' => 'roles.edit', 'nombre' => 'Editar roles'],
            ['slug' => 'roles.delete', 'nombre' => 'Eliminar roles'],
        ];

        foreach ($permisosRoles as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $moduloRoles->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        // Crear permisos para sistema
        $permisosSistema = [
            ['slug' => 'inicio.view', 'nombre' => 'Ver dashboard'],
            ['slug' => 'modulos.index', 'nombre' => 'Ver módulos'],
            ['slug' => 'sistema.configuracion', 'nombre' => 'Configurar sistema'],
            ['slug' => 'sistema.logs', 'nombre' => 'Ver logs del sistema'],
            ['slug' => 'sistema.backup', 'nombre' => 'Realizar backups'],
        ];

        foreach ($permisosSistema as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $moduloSistema->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        // Crear roles básicos
        $roleSuperAdmin = Role::create([
            'nombre' => 'Super Administrador',
            'descripcion' => 'Acceso completo al sistema'
        ]);


        $modulo_carrusel = Module::create([
            'slug' => 'carrusel',
            'nombre' => 'Carrusel',
            'descripcion' => 'Gestión del carrusel de imágenes'
        ]);

        $permisosCarrusel = [
            ['slug' => 'carrusel.index', 'nombre' => 'Ver listado de carrusel'],
            ['slug' => 'carrusel.show', 'nombre' => 'Ver detalles del carrusel'],
            ['slug' => 'carrusel.create', 'nombre' => 'Crear elementos del carrusel'],
            ['slug' => 'carrusel.edit', 'nombre' => 'Editar elementos del carrusel'],
            ['slug' => 'carrusel.delete', 'nombre' => 'Eliminar elementos del carrusel'],
            ['slug' => 'carrusel.estado', 'nombre' => 'Cambiar estado del carrusel'],
        ];
        foreach ($permisosCarrusel as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_carrusel->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        // Asignar todos los permisos al Super Administrador
        $todosPermisos = Permission::all();
        $roleSuperAdmin->permissions()->attach($todosPermisos->pluck('id'));


        $modulo_categoria = Module::create([
            'slug' => 'categoria',
            'nombre' => 'Categorías',
            'descripcion' => 'Gestión de categorías'
        ]);

        $permisosCategoria = [
            ['slug' => 'categorias.index', 'nombre' => 'Ver listado de categorías'],
            ['slug' => 'categorias.show', 'nombre' => 'Ver detalles de categoría'],
            ['slug' => 'categorias.create', 'nombre' => 'Crear categorías'],
            ['slug' => 'categorias.edit', 'nombre' => 'Editar categorías'],
            ['slug' => 'categorias.delete', 'nombre' => 'Eliminar categorías'],
            ['slug' => 'categorias.estado', 'nombre' => 'Cambiar estado de categorías'],

        ];

        foreach ($permisosCategoria as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_categoria->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        $asignar_permisos_categoria = Permission::where('module_id', $modulo_categoria->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_categoria->pluck('id'));

        $modulo_marcas = Module::create([
            'slug' => 'marcas',
            'nombre' => 'Marcas',
            'descripcion' => 'Gestión de marcas'
        ]);

        $permisosMarcas = [
            ['slug' => 'marcas.index', 'nombre' => 'Ver listado de marcas'],
            ['slug' => 'marcas.show', 'nombre' => 'Ver detalles de marca'],
            ['slug' => 'marcas.create', 'nombre' => 'Crear marcas'],
            ['slug' => 'marcas.edit', 'nombre' => 'Editar marcas'],
            ['slug' => 'marcas.delete', 'nombre' => 'Eliminar marcas'],
            ['slug' => 'marcas.estado', 'nombre' => 'Cambiar estado de marcas'],

        ];

        foreach ($permisosMarcas as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_marcas->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        $asignar_permisos_marcas = Permission::where('module_id', $modulo_marcas->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_marcas->pluck('id'));

        $modulo_colores = Module::create([
            'slug' => 'colores',
            'nombre' => 'Colores',
            'descripcion' => 'Gestión de colores'
        ]);

        $permisosColores = [
            ['slug' => 'colores.index', 'nombre' => 'Ver listado de colores'],
            ['slug' => 'colores.show', 'nombre' => 'Ver detalles de color'],
            ['slug' => 'colores.create', 'nombre' => 'Crear colores'],
            ['slug' => 'colores.edit', 'nombre' => 'Editar colores'],
            ['slug' => 'colores.delete', 'nombre' => 'Eliminar colores'],
            ['slug' => 'colores.estado', 'nombre' => 'Cambiar estado de colores'],

        ];

        foreach ($permisosColores as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_colores->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        $asignar_permisos_colores = Permission::where('module_id', $modulo_colores->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_colores->pluck('id'));

        $modulo_tallas = Module::create([
            'slug' => 'tallas',
            'nombre' => 'Tallas',
            'descripcion' => 'Gestión de tallas'
        ]);

        $permisosTallas = [
            ['slug' => 'tallas.index', 'nombre' => 'Ver listado de tallas'],
            ['slug' => 'tallas.show', 'nombre' => 'Ver detalles de talla'],
            ['slug' => 'tallas.create', 'nombre' => 'Crear tallas'],
            ['slug' => 'tallas.edit', 'nombre' => 'Editar tallas'],
            ['slug' => 'tallas.delete', 'nombre' => 'Eliminar tallas'],
            ['slug' => 'tallas.estado', 'nombre' => 'Cambiar estado de tallas'],

        ];

        foreach ($permisosTallas as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_tallas->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        $asignar_permisos_tallas = Permission::where('module_id', $modulo_tallas->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_tallas->pluck('id'));

        $modulo_medidas = Module::create([
            'slug' => 'medidas',
            'nombre' => 'Medidas',
            'descripcion' => 'Gestión de medidas'
        ]);

        $permisosMedidas = [
            ['slug' => 'medidas.index', 'nombre' => 'Ver listado de medidas'],
            ['slug' => 'medidas.show', 'nombre' => 'Ver detalles de medida'],
            ['slug' => 'medidas.create', 'nombre' => 'Crear medidas'],
            ['slug' => 'medidas.edit', 'nombre' => 'Editar medidas'],
            ['slug' => 'medidas.delete', 'nombre' => 'Eliminar medidas'],
            ['slug' => 'medidas.estado', 'nombre' => 'Cambiar estado de medidas'],

        ];

        foreach ($permisosMedidas as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_medidas->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }
        $asignar_permisos_medidas = Permission::where('module_id', $modulo_medidas->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_medidas->pluck('id'));

        $modulo_sabores = Module::create([
            'slug' => 'sabores',
            'nombre' => 'Sabores',
            'descripcion' => 'Gestión de sabores'
        ]);

        $permisosSabores = [
            ['slug' => 'sabores.index', 'nombre' => 'Ver listado de sabores'],
            ['slug' => 'sabores.show', 'nombre' => 'Ver detalles de sabor'],
            ['slug' => 'sabores.create', 'nombre' => 'Crear sabores'],
            ['slug' => 'sabores.edit', 'nombre' => 'Editar sabores'],
            ['slug' => 'sabores.delete', 'nombre' => 'Eliminar sabores'],
            ['slug' => 'sabores.estado', 'nombre' => 'Cambiar estado de sabores'],

        ];
        foreach ($permisosSabores as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_sabores->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }
        $asignar_permisos_sabores = Permission::where('module_id', $modulo_sabores->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_sabores->pluck('id'));

        $modulo_modelos = Module::create([
            'slug' => 'modelos',
            'nombre' => 'Modelos',
            'descripcion' => 'Gestión de modelos'
        ]);
        $permisosModelos = [
            ['slug' => 'modelos.index', 'nombre' => 'Ver listado de modelos'],
            ['slug' => 'modelos.show', 'nombre' => 'Ver detalles de modelo'],
            ['slug' => 'modelos.create', 'nombre' => 'Crear modelos'],
            ['slug' => 'modelos.edit', 'nombre' => 'Editar modelos'],
            ['slug' => 'modelos.delete', 'nombre' => 'Eliminar modelos'],
            ['slug' => 'modelos.estado', 'nombre' => 'Cambiar estado de modelos'],

        ];

        foreach ($permisosModelos as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_modelos->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }

        $asignar_permisos_modelos = Permission::where('module_id', $modulo_modelos->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_modelos->pluck('id'));

        $modulo_tono = Module::create([
            'slug' => 'tonos',
            'nombre' => 'Tonos',
            'descripcion' => 'Gestión de tonos'
        ]);
        $permisosTonos = [
            ['slug' => 'tonos.index', 'nombre' => 'Ver listado de tonos'],
            ['slug' => 'tonos.show', 'nombre' => 'Ver detalles de tono'],
            ['slug' => 'tonos.create', 'nombre' => 'Crear tonos'],
            ['slug' => 'tonos.edit', 'nombre' => 'Editar tonos'],
            ['slug' => 'tonos.delete', 'nombre' => 'Eliminar tonos'],
            ['slug' => 'tonos.estado', 'nombre' => 'Cambiar estado de tonos'],
        ];
        foreach ($permisosTonos as $permiso) {
            Permission::create([
                'slug' => $permiso['slug'],
                'nombre' => $permiso['nombre'],
                'module_id' => $modulo_tono->id,
                'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
            ]);
        }
        $asignar_permisos_tonos = Permission::where('module_id', $modulo_tono->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();
        $roleSuperAdmin->permissions()->attach($asignar_permisos_tonos->pluck('id'));

        // Crear módulo Artículos (si no existe)
        $modulo_articulos = Module::where('slug', 'articulos')->first();
        if (!$modulo_articulos) {
            $modulo_articulos = Module::create([
                'slug' => 'articulos',
                'nombre' => 'Artículos',
                'descripcion' => 'Gestión de artículos, variantes e inventario'
            ]);
        }

        // Crear permisos para Artículos (si no existen)
        $permisosArticulos = [
            ['slug' => 'articulos.index', 'nombre' => 'Ver listado de artículos'],
            ['slug' => 'articulos.show', 'nombre' => 'Ver detalles de artículo'],
            ['slug' => 'articulos.create', 'nombre' => 'Crear artículos'],
            ['slug' => 'articulos.edit', 'nombre' => 'Editar artículos'],
            ['slug' => 'articulos.delete', 'nombre' => 'Eliminar artículos'],
            ['slug' => 'articulos.estado', 'nombre' => 'Cambiar estado de artículos'],
            ['slug' => 'articulos.crear_variantes', 'nombre' => 'Crear variantes de artículos'],
            ['slug' => 'articulos.editar_variantes', 'nombre' => 'Editar variantes de artículos'],
            ['slug' => 'articulos.eliminar_variantes', 'nombre' => 'Eliminar variantes de artículos'],
            ['slug' => 'articulos.ver_stock', 'nombre' => 'Ver información de stock'],
            ['slug' => 'articulos.reservar_stock', 'nombre' => 'Reservar stock de artículos'],
            ['slug' => 'articulos.liberar_stock', 'nombre' => 'Liberar stock reservado'],
            ['slug' => 'articulos.decrementar_stock', 'nombre' => 'Decrementar stock de artículos'],
        ];

        foreach ($permisosArticulos as $permiso) {
            Permission::firstOrCreate(
                ['slug' => $permiso['slug']],
                [
                    'nombre' => $permiso['nombre'],
                    'module_id' => $modulo_articulos->id,
                    'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
                ]
            );
        }

        // Asignar permisos al Super Administrador (si existe)
        $asignar_permisos_articulos = Permission::where('module_id', $modulo_articulos->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->orWhere('nombre', 'Admin')->first();

        if ($roleSuperAdmin) {
            $roleSuperAdmin->permissions()->syncWithoutDetaching($asignar_permisos_articulos->pluck('id'));
        }

        $modulo_plazas = Module::create([
            'slug' => 'plazas',
            'nombre' => 'Plazas',
            'descripcion' => 'Gestión de plazas'
        ]);

        // Crear permisos para Plazas (si no existen)
        $permisosPlazas = [
            ['slug' => 'plazas.index', 'nombre' => 'Ver listado de plazas'],
            ['slug' => 'plazas.show', 'nombre' => 'Ver detalles de plaza'],
            ['slug' => 'plazas.create', 'nombre' => 'Crear plazas'],
            ['slug' => 'plazas.edit', 'nombre' => 'Editar plazas'],
            ['slug' => 'plazas.delete', 'nombre' => 'Eliminar plazas'],
            ['slug' => 'plazas.estado', 'nombre' => 'Cambiar estado de plazas'],
        ];

        foreach ($permisosPlazas as $permiso) {
            Permission::firstOrCreate(
                ['slug' => $permiso['slug']],
                [
                    'nombre' => $permiso['nombre'],
                    'module_id' => $modulo_plazas->id,
                    'descripcion' => 'Permite ' . strtolower($permiso['nombre'])
                ]
            );
        }

        // Asignar permisos al Super Administrador (si existe)
        $asignar_permisos_plazas = Permission::where('module_id', $modulo_plazas->id)->get();
        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->orWhere('nombre', 'Admin')->first();
        if ($roleSuperAdmin) {
            $roleSuperAdmin->permissions()->syncWithoutDetaching($asignar_permisos_plazas->pluck('id'));
        }
    }
}
//php artisan db:seed --class=ModuloPermisosSeed

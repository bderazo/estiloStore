<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class NosotrosPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $module = Module::firstOrCreate(
            ['slug' => 'nosotros'],
            [
                'nombre' => 'Nosotros',
                'descripcion' => 'Gestión de la sección Nosotros',
            ]
        );

        $permissions = collect([
            [
                'slug' => 'view nosotros',
                'nombre' => 'Ver sección Nosotros',
                'descripcion' => 'Permite ver la información de la sección Nosotros',
            ],
            [
                'slug' => 'manage nosotros',
                'nombre' => 'Administrar sección Nosotros',
                'descripcion' => 'Permite crear o actualizar la información de la sección Nosotros',
            ],
        ]);

        $permissions->each(function (array $permission) use ($module) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                [
                    'nombre' => $permission['nombre'],
                    'descripcion' => $permission['descripcion'],
                    'module_id' => $module->id,
                ]
            );
        });

        $role = Role::where('nombre', 'Super Administrador')->orWhere('nombre', 'Admin')->first();

        if ($role) {
            $permissionIds = Permission::whereIn('slug', $permissions->pluck('slug'))->pluck('id');
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }
    }
}
// php artisan db:seed --class=NosotrosPermissionsSeeder

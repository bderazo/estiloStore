<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roleSuperAdmin = Role::where('nombre', 'Super Administrador')->first();

        User::create([
            'numero_documento' => '99999999',
            'name' => 'Estilo Store',
            'nombres' => 'Estilo',
            'apellidos' => 'Store',
            'email' => 'super_admin@admin.com',
            'password' => Hash::make('admin1234'),
            'email_verified_at' => now(),
            'role_id' => $roleSuperAdmin->id
        ]);

        $this->command->info('✅ Sistema inicializado correctamente:');
        $this->command->info('📊 Módulos creados: ' . Module::count());
        $this->command->info('🔐 Permisos creados: ' . Permission::count());
        $this->command->info('👥 Roles creados: ' . Role::count());
        $this->command->info('👤 Usuarios creados: ' . User::count());
        $this->command->info('');
        $this->command->info('🔑 Usuario administrador:');
        $this->command->info('Email: super_admin@admin.com');
        $this->command->info('Password: admin');
        $this->command->info('Permisos: TODOS (' . Permission::count() . ' permisos)');
        $this->command->info('');
        $this->command->info('📋 Roles disponibles para asignar:');
        $this->command->info('• Super Administrador (todos los permisos)');
        $this->command->info('• Administrador (gestión de usuarios)');
        $this->command->info('• Editor (permisos básicos)');
        $this->command->info('• Usuario (solo dashboard)');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ReferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Categorías
        DB::table('categorias')->insert([
            ['nombre' => 'Bebidas', 'slug' => 'bebidas', 'descripcion' => 'Bebidas en general', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Comida', 'slug' => 'comida', 'descripcion' => 'Comida y alimentos', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Postres', 'slug' => 'postres', 'descripcion' => 'Postres y dulces', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Bebidas Calientes', 'slug' => 'bebidas-calientes', 'descripcion' => 'Café, té, chocolate', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Bebidas Frías', 'slug' => 'bebidas-frias', 'descripcion' => 'Jugos, refrescos', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Marcas
        DB::table('marcas')->insert([
            ['nombre' => 'Coca Cola', 'slug' => 'coca-cola', 'descripcion' => 'Bebidas Coca Cola', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Pepsi', 'slug' => 'pepsi', 'descripcion' => 'Bebidas Pepsi', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Fanta', 'slug' => 'fanta', 'descripcion' => 'Bebidas Fanta', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Sprite', 'slug' => 'sprite', 'descripcion' => 'Bebidas Sprite', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Premium', 'slug' => 'premium', 'descripcion' => 'Marca Premium', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Colores (solo nombre, codigo_hex, activo)
        DB::table('colores')->insert([
            ['nombre' => 'Rojo', 'codigo_hex' => '#FF0000', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Azul', 'codigo_hex' => '#0000FF', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Verde', 'codigo_hex' => '#00FF00', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Amarillo', 'codigo_hex' => '#FFFF00', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Negro', 'codigo_hex' => '#000000', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Blanco', 'codigo_hex' => '#FFFFFF', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Tallas (solo nombre, activo)
        DB::table('tallas')->insert([
            ['nombre' => 'XS', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'S', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'M', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'L', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'XL', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'XXL', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Plazas (solo nombre, activo)
        DB::table('plazas')->insert([
            ['nombre' => '24 horas', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => '48 horas', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => '72 horas', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => '1 semana', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => '2 semanas', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Medidas (solo nombre, activo)
        DB::table('medidas')->insert([
            ['nombre' => 'Litros', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Mililitros', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Kilogramos', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Gramos', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Unidades', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Sabores (solo nombre, activo)
        DB::table('sabores')->insert([
            ['nombre' => 'Dulce', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Salado', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Picante', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Amargo', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Ácido', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Fresa', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Vainilla', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Chocolate', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Modelos (solo nombre, activo)
        DB::table('modelos')->insert([
            ['nombre' => 'Modelo Clásico', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Modelo Moderno', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Modelo Premium', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Modelo Deportivo', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Tonos (solo nombre, activo)
        DB::table('tonos')->insert([
            ['nombre' => 'Claro', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Oscuro', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Medio', 'activo' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}

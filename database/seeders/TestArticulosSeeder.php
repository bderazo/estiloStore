<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Support\Str;

class TestArticulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();

        if ($categorias->isEmpty() || $marcas->isEmpty()) {
            $this->command->warn('No hay categorías o marcas en la BD');
            return;
        }

        $articulos_datos = [
            ['nombre' => 'Zapatillas Nike Air Max', 'precio' => 120, 'sku' => 'ZAP-NKE-001'],
            ['nombre' => 'Zapatillas Adidas Running', 'precio' => 95, 'sku' => 'ZAP-ADS-001'],
            ['nombre' => 'Zapatillas Puma Speed', 'precio' => 85, 'sku' => 'ZAP-PUM-001'],
            ['nombre' => 'Zapatillas New Balance 574', 'precio' => 100, 'sku' => 'ZAP-NB-001'],
            ['nombre' => 'Zapatillas Skechers Comfort', 'precio' => 75, 'sku' => 'ZAP-SKE-001'],
            ['nombre' => 'Zapatillas Vans Classic', 'precio' => 65, 'sku' => 'ZAP-VAN-001'],
            ['nombre' => 'Zapatillas Converse All Star', 'precio' => 55, 'sku' => 'ZAP-CON-001'],
            ['nombre' => 'Zapatillas Jordan Air', 'precio' => 150, 'sku' => 'ZAP-JOR-001'],
            ['nombre' => 'Zapatillas Reebok Classic', 'precio' => 70, 'sku' => 'ZAP-REB-001'],
            ['nombre' => 'Zapatillas Asics Gel', 'precio' => 110, 'sku' => 'ZAP-ASI-001'],
            ['nombre' => 'Zapatillas Under Armour', 'precio' => 105, 'sku' => 'ZAP-UA-001'],
            ['nombre' => 'Zapatillas Salomon Trail', 'precio' => 130, 'sku' => 'ZAP-SAL-001'],
        ];

        foreach ($articulos_datos as $dato) {
            Articulo::create([
                'nombre' => $dato['nombre'],
                'slug' => Str::slug($dato['nombre']),
                'descripcion' => 'Descripción del producto: ' . $dato['nombre'],
                'especificaciones' => 'Material de calidad premium',
                'precio' => $dato['precio'],
                'sku' => $dato['sku'],
                'categoria_id' => $categorias->random()->id,
                'marca_id' => $marcas->random()->id,
                'activo' => true,
                'destacado' => false,
            ]);
            $this->command->info('Creado: ' . $dato['nombre']);
        }

        $this->command->info('Artículos de prueba creados exitosamente');
    }
}

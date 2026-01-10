<?php

namespace Database\Seeders;

use App\Models\Articulo;
use App\Models\ArticuloImagen;
use App\Models\ArticuloVariante;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Color;
use App\Models\Talla;
use App\Models\Sabor;
use App\Models\Modelo;
use Illuminate\Database\Seeder;

class ArticulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener referencias
        $categorias = Categoria::all();
        $marcas = Marca::all();
        $colores = Color::all();
        $tallas = Talla::all();
        $sabores = Sabor::all();
        $modelos = Modelo::all();

        // 1. ZAPATILLAS con variantes de tallas
        if ($categorias->where('nombre', 'Calzado')->first() && $marcas->first()) {
            $zapatillas = Articulo::create([
                'nombre' => 'Zapatillas Deportivas Air Max',
                'slug' => 'zapatillas-air-max',
                'descripcion' => 'Zapatillas cómodas para deportes',
                'especificaciones' => 'Material: Tela + Sintético, Suela: Goma',
                'precio' => 89.99,
                'sku' => 'ZAP-001',
                'categoria_id' => $categorias->where('nombre', 'Calzado')->first()->id,
                'marca_id' => $marcas->first()->id,
                'activo' => true,
                'destacado' => true,
            ]);

            // Agregar imagen
            ArticuloImagen::create([
                'articulo_id' => $zapatillas->id,
                'url' => 'https://via.placeholder.com/300x300?text=Zapatillas',
                'orden' => 1,
            ]);

            // Agregar variantes con diferentes tallas
            $talla_sizes = ['XS', 'S', 'M', 'L', 'XL'];
            foreach ($talla_sizes as $index => $size) {
                $talla = $tallas->where('nombre', $size)->first();
                if ($talla) {
                    ArticuloVariante::create([
                        'articulo_id' => $zapatillas->id,
                        'sku' => 'ZAP-001-' . $size,
                        'precio_override' => 89.99 + ($index * 5),
                        'stock' => ($index === 4 ? 10 : 15), // Talla XL: 10 unidades
                        'reservado' => 0,
                        'atributos' => json_encode(['talla' => $size]),
                        'activo' => true,
                    ]);
                }
            }

            // Agregar relación many-to-many con colores
            $zapatillas->colores()->attach($colores->take(3)->pluck('id'));
            $zapatillas->tallas()->attach($tallas->take(5)->pluck('id'));
        }

        // 2. PERFUME con variantes de sabores
        if ($categorias->where('nombre', 'Fragancias')->first() && $marcas->skip(1)->first()) {
            $perfume = Articulo::create([
                'nombre' => 'Perfume Premium Fresco',
                'slug' => 'perfume-fresco',
                'descripcion' => 'Perfume de larga duración con múltiples aromas',
                'especificaciones' => 'Volumen: 100ml, Tipo: Eau de Toilette',
                'precio' => 45.99,
                'sku' => 'PER-001',
                'categoria_id' => $categorias->where('nombre', 'Fragancias')->first()->id ?? $categorias->first()->id,
                'marca_id' => $marcas->skip(1)->first()->id ?? $marcas->first()->id,
                'activo' => true,
                'destacado' => true,
            ]);

            // Agregar imagen
            ArticuloImagen::create([
                'articulo_id' => $perfume->id,
                'url' => 'https://via.placeholder.com/300x300?text=Perfume',
                'orden' => 1,
            ]);

            // Agregar variantes con diferentes sabores
            $sabores_list = ['Fresa', 'Vainilla', 'Cítrico'];
            $precios = [45.99, 49.99, 48.99];

            foreach ($sabores_list as $index => $sabor_name) {
                $sabor = $sabores->where('nombre', $sabor_name)->first();
                if ($sabor) {
                    ArticuloVariante::create([
                        'articulo_id' => $perfume->id,
                        'sku' => 'PER-001-' . strtoupper(substr($sabor_name, 0, 3)),
                        'precio_override' => $precios[$index],
                        'stock' => 20,
                        'reservado' => 0,
                        'atributos' => json_encode(['sabor' => $sabor_name]),
                        'activo' => true,
                    ]);
                }
            }

            // Agregar relación many-to-many
            $perfume->colores()->attach($colores->skip(2)->take(2)->pluck('id'));
            $perfume->sabores()->attach($sabores->take(3)->pluck('id'));
        }

        // 3. CAMISA con variantes de colores y tallas
        if ($categorias->where('nombre', 'Ropa')->first()) {
            $camisa = Articulo::create([
                'nombre' => 'Camisa Casual Clásica',
                'slug' => 'camisa-casual',
                'descripcion' => 'Camisa versátil para cualquier ocasión',
                'especificaciones' => 'Material: 100% Algodón',
                'precio' => 34.99,
                'sku' => 'CAM-001',
                'categoria_id' => $categorias->where('nombre', 'Ropa')->first()->id ?? $categorias->first()->id,
                'marca_id' => $marcas->skip(2)->first()->id ?? $marcas->first()->id,
                'activo' => true,
                'destacado' => false,
            ]);

            // Agregar imagen
            ArticuloImagen::create([
                'articulo_id' => $camisa->id,
                'url' => 'https://via.placeholder.com/300x300?text=Camisa',
                'orden' => 1,
            ]);

            // Variantes: Colores x Tallas
            $colores_list = ['Rojo', 'Azul', 'Negro'];
            $tallas_list = ['M', 'L', 'XL'];

            foreach ($colores_list as $color_name) {
                foreach ($tallas_list as $talla_name) {
                    $color = $colores->where('nombre', $color_name)->first();
                    $talla = $tallas->where('nombre', $talla_name)->first();

                    if ($color && $talla) {
                        ArticuloVariante::create([
                            'articulo_id' => $camisa->id,
                            'sku' => 'CAM-001-' . substr($color_name, 0, 2) . $talla_name,
                            'precio_override' => 34.99,
                            'stock' => 25,
                            'reservado' => 0,
                            'atributos' => json_encode(['color' => $color_name, 'talla' => $talla_name]),
                            'activo' => true,
                        ]);
                    }
                }
            }

            // Agregar relaciones many-to-many
            $camisa->colores()->attach($colores->take(3)->pluck('id'));
            $camisa->tallas()->attach($tallas->skip(2)->take(3)->pluck('id'));
        }

        // 4. RELOJ con variantes de modelos
        if ($categorias->where('nombre', 'Accesorios')->first()) {
            $reloj = Articulo::create([
                'nombre' => 'Reloj Inteligente Sport',
                'slug' => 'reloj-sport',
                'descripcion' => 'Reloj inteligente con múltiples funciones',
                'especificaciones' => 'Pantalla: AMOLED, Duración: 7 días, Resistencia: 5ATM',
                'precio' => 199.99,
                'sku' => 'REL-001',
                'categoria_id' => $categorias->where('nombre', 'Accesorios')->first()->id ?? $categorias->first()->id,
                'marca_id' => $marcas->skip(3)->first()->id ?? $marcas->first()->id,
                'activo' => true,
                'destacado' => true,
            ]);

            // Agregar imagen
            ArticuloImagen::create([
                'articulo_id' => $reloj->id,
                'url' => 'https://via.placeholder.com/300x300?text=Reloj',
                'orden' => 1,
            ]);

            // Variantes con diferentes modelos
            $modelos_list = ['Sport', 'Clásico', 'Business'];
            $precios_modelo = [199.99, 249.99, 299.99];

            foreach ($modelos_list as $index => $modelo_name) {
                $modelo = $modelos->where('nombre', $modelo_name)->first();
                if ($modelo) {
                    ArticuloVariante::create([
                        'articulo_id' => $reloj->id,
                        'sku' => 'REL-001-' . strtoupper(substr($modelo_name, 0, 3)),
                        'precio_override' => $precios_modelo[$index],
                        'stock' => 12,
                        'reservado' => 0,
                        'atributos' => json_encode(['modelo' => $modelo_name]),
                        'activo' => true,
                    ]);
                }
            }

            // Agregar relaciones
            $reloj->modelos()->attach($modelos->take(3)->pluck('id'));
            $reloj->colores()->attach($colores->take(2)->pluck('id'));
        }
    }
}

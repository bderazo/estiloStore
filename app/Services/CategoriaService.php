<?php

namespace App\Services;

use App\Models\Categoria;
use App\Models\Articulo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriaService
{
    /**
     * Obtiene el menú de la tienda (Estructura jerárquica)
     */
    public function getMenuTienda()
    {
        return Categoria::active()
            ->whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->active()->orderBy('orden');
            }])
            ->orderBy('orden')
            ->get();
    }

    /**
     * Obtiene artículos filtrados por categoría (incluye subcategorías)
     */
    public function getArticulosPorCategoria(string $slug)
    {
        $categoria = Categoria::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $ids = $categoria->getAllIds();

        $articulos = Articulo::active()
            ->whereIn('categoria_id', $ids)
            ->with(['imagenes', 'marca']) 
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return [
            'categoria' => $categoria,
            'articulos' => $articulos
        ];
    }

    /**
     * NUEVO: Lógica para guardar o actualizar una categoría con imagen
     */
    public function storeOrUpdate(array $data, $id = null)
    {
        // Generar slug automáticamente si no viene o si es nueva
        $data['slug'] = Str::slug($data['nombre']);

        if ($id) {
            $categoria = Categoria::findOrFail($id);
            
            // Si viene una imagen nueva, borrar la anterior
            if (isset($data['imagen_file'])) {
                if ($categoria->imagen) {
                    Storage::disk('public')->delete($categoria->imagen);
                }
                $data['imagen'] = $data['imagen_file']->store('categorias', 'public');
            }
            
            $categoria->update($data);
            return $categoria;
        }

        // Crear nueva
        if (isset($data['imagen_file'])) {
            $data['imagen'] = $data['imagen_file']->store('categorias', 'public');
        }

        return Categoria::create($data);
    }

    /**
     * NUEVO: Obtener categorías destacadas para el Home (las que tienen imagen)
     */
    public function getCategoriasHome()
    {
        return Categoria::active()
            ->whereNull('parent_id')
            ->whereNotNull('imagen')
            ->orderBy('orden')
            ->take(5) // El número de cards que definimos en tu diseño
            ->get();
    }
    public function getAllActiveWithCount() {
    return Categoria::where('activo', true)
        ->withCount('articulos') // Esto requiere que tengas la relación articulos() en el modelo Categoria
        ->get();
}
}
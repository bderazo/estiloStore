<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'parent_id',
        'activo',
        'orden'
    ];

    // Relación recursiva (categorías y subcategorías)
    public function parent()
    {
        return $this->belongsTo(Categoria::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Categoria::class, 'parent_id');
    }

    // Relación con artículos
    /*  public function articulos()
    {
        return $this->hasMany(Articulo::class);
    } */

    // Scope para categorías principales (sin parent)
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope para activos
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtener la ruta jerárquica completa de la categoría
     */
    public function getPathAttribute(): string
    {
        $path = [];
        $categoria = $this;

        // Recorrer hacia arriba hasta llegar a la raíz
        while ($categoria) {
            array_unshift($path, $categoria->nombre);
            $categoria = $categoria->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Obtener todos los ancestros de la categoría
     */
    public function getAncestors()
    {
        $ancestors = collect();
        $categoria = $this->parent;

        while ($categoria) {
            $ancestors->push($categoria);
            $categoria = $categoria->parent;
        }

        return $ancestors;
    }

    /**
     * Obtener todos los descendientes de la categoría
     */
    public function getDescendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getDescendants());
        }

        return $descendants;
    }

    /**
     * Obtener el nivel de profundidad de la categoría
     */
    public function getLevelAttribute(): int
    {
        $level = 0;
        $categoria = $this->parent;

        while ($categoria) {
            $level++;
            $categoria = $categoria->parent;
        }

        return $level;
    }

    /**
     * Scope para traer categorías con sus hijos de forma eficiente
     */
    public function scopeWithChildren($query)
    {
        return $query->main() // Solo las que tienen parent_id null
            ->active() // Solo las activas
            ->with(['children' => function ($q) {
                $q->active()->orderBy('orden', 'asc');
            }])
            ->orderBy('orden', 'asc');
    }

    public function articulos() {
        return $this->hasMany(Articulo::class, 'categoria_id');
    }

    // Helper para obtener todos los IDs (hijos y nietos)
    public function getAllIds()
    {
        // Obtiene su propio ID y el de todos sus hijos recursivamente
        return collect([$this->id])->merge($this->children->flatMap(function($child) {
            return $child->getAllIds();
        }))->all();
    }
    
}


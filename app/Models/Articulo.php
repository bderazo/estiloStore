<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articulo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'articulos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'especificaciones',
        'precio',
        'sku',
        'categoria_id',
        'marca_id',
        'activo',
        'destacado',
        'precio_oferta',
        'porcentaje_descuento',
        'en_oferta',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
        'destacado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con la categoría
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación con la marca
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Relación con las imágenes
     */
    public function imagenes(): HasMany
    {
        return $this->hasMany(ArticuloImagen::class)->orderBy('orden');
    }

    /**
     * Obtener imagen principal (primera según orden)
     */
    public function imagenPrincipal()
    {
        return $this->hasOne(ArticuloImagen::class)->orderBy('orden')->limit(1);
    }

    /**
     * Relación con las variantes
     */
    public function variantes(): HasMany
    {
        return $this->hasMany(ArticuloVariante::class);
    }

    /**
     * Relación many-to-many con colores
     */
    public function colores(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'articulo_color');
    }

    /**
     * Relación many-to-many con tallas
     */
    public function tallas(): BelongsToMany
    {
        return $this->belongsToMany(Talla::class, 'articulo_talla');
    }

    /**
     * Relación many-to-many con plazas
     */
    public function plazas(): BelongsToMany
    {
        return $this->belongsToMany(Plaza::class, 'articulo_plaza');
    }

    /**
     * Relación many-to-many con medidas
     */
    public function medidas(): BelongsToMany
    {
        return $this->belongsToMany(Medida::class, 'articulo_medida');
    }

 
    /**
     * Relación many-to-many con sabores
     */
    public function sabores(): BelongsToMany
    {
        return $this->belongsToMany(Sabor::class, 'articulo_sabor');
    }

    /**
     * Relación many-to-many con modelos
     */
    public function modelos(): BelongsToMany
    {
        return $this->belongsToMany(Modelo::class, 'articulo_modelo');
    }

    /**
     * Relación many-to-many con tonos
     */
    public function tonos(): BelongsToMany
    {
        return $this->belongsToMany(Tono::class, 'articulo_tono');
    }

    /**
     * Scope para filtrar por activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por destacados
     */
    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    /**
     * Scope para buscar por nombre, descripción o SKU
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nombre', 'like', "%{$search}%")
            ->orWhere('descripcion', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%");
    }

    /**
     * Scope para filtrar por rango de precio
     */
    public function scopePrecioEntre($query, $minimo, $maximo)
    {
        return $query->whereBetween('precio', [$minimo, $maximo]);
    }

    /**
     * Obtener stock total (suma de stock de todas las variantes)
     */
    public function getStockTotalAttribute(): int
    {
        return $this->variantes->sum('stock');
    }

    /**
     * Obtener stock disponible total (suma de available de variantes)
     */
    public function getStockDisponibleTotalAttribute(): int
    {
        return $this->variantes->sum(function ($variante) {
            return $variante->available;
        });
    }
    // Acceso rápido a la imagen principal
    public function getImagenPrincipalAttribute() {
        $img = ArticuloImagen::where('articulo_id','=',$this->id)->where('is_active','=',1)->orderBy('orden')->first();
        return $img ? $img->ruta : 'default.png';
    }

        // Scope para activos

    public function scopeActive($query)
    {

        return $query->where('activo', true);

    }
    public function scopeOfertas($query)
    {
        return $query->where('en_oferta', true)->whereNotNull('precio_oferta');
    }
}

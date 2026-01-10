<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticuloVariante extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * Eliminados 'sku' y 'precio' - se manejan a nivel de artículo
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'articulo_id',
        'color_id',   // Llave foránea a colores
        'talla_id',   // Llave foránea a tallas
        'stock',      // Cantidad real de esta combinación
        'sku_variante' // Opcional: código específico para la variante
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'atributos' => 'array',
        'stock' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el artículo
     */
    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class);
    }

    /**
     * Obtener el precio final (siempre del artículo base)
     * Las variantes ya no manejan precio individual
     */
    public function getPrecioFinalAttribute()
    {
        return $this->articulo?->precio;
    }

    /**
     * Obtener stock disponible
     */
    public function getAvailableAttribute(): int
    {
        return $this->stock;
    }

    /**
     * Scope para filtrar por activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por stock disponible
     */
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Validar que hay stock disponible
     */
    public function tieneStock(int $cantidad): bool
    {
        return $this->available >= $cantidad;
    }

    /**
     * Validar que hay stock total (incluyendo reserved)
     */
    public function tieneStockTotal(int $cantidad): bool
    {
        return $this->stock >= $cantidad;
    }

    // Relación inversa para saber en qué órdenes está esta variante
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'articulo_variante_id');
    }
    public function color() {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function talla() {
        return $this->belongsTo(Talla::class, 'talla_id');
    }
}

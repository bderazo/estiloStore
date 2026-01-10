<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticuloImagen extends Model
{
    use HasFactory;

    protected $table = 'articulo_imagenes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'articulo_id',
        'ruta',
        'orden',
        'metadatos',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadatos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el artículo
     */
    public function articulo(): BelongsTo
    {
        //return $this->belongsTo(Articulo::class);
        return $this->belongsTo(Articulo::class, 'articulo_id', 'id');
    }
}

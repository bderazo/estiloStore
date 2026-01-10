<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    /* public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_talla');
    } */

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}

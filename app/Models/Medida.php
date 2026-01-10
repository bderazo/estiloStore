<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'activo'
    ];

    /* public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_medida');
    } */

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}

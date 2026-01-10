<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'activo'
    ];

   /*  public function articulos()
    {
        return $this->hasMany(Articulo::class);
    } */

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}

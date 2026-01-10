<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colores';

    protected $fillable = [
        'nombre',
        'codigo_hex',
        'activo'
    ];

   /*  public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_color');
    } */

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}

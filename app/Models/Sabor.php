<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sabor extends Model
{
    use HasFactory;

    protected $table = 'sabores';

    protected $fillable = [
        'nombre',
        'activo'
    ];

   /*  public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_sabor');
    } */

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}

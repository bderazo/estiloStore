<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $table = 'modelos';

    protected $fillable = [
        'nombre',
        'activo'
    ];

    /* public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_modelo');
    } */

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}

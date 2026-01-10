<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaza extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'activo'
    ];

    /* public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_plaza');
    } */

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}

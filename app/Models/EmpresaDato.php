<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaDato extends Model
{
    protected $table = 'empresa_datos';
    protected $fillable = ['clave', 'titulo', 'contenido', 'imagen', 'activo'];

    // Scope para traer solo lo activo
    public function scopeActive($query) {
        return $query->where('activo', true);
    }
}
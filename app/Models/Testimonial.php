<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'cargo',
        'testimonio',
        'imagen',
        'rating',
        'orden',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'rating' => 'integer',
        'orden' => 'integer'
    ];

    // Scope para ordenar
    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden')->orderBy('id');
    }

    // Scope para activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Obtener URL de imagen
    public function getImagenUrlAttribute(): string
    {
        if (!$this->imagen) {
            return asset('web/assets/img/testimonial-placeholder.jpg');
        }

        if (str_starts_with($this->imagen, 'http')) {
            return $this->imagen;
        }

        return asset('storage/' . $this->imagen);
    }
}
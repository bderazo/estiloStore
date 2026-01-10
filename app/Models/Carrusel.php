<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrusel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carrusel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'subtitulo',
        'activar_subtitulo',
        'activar_boton',
        'url_boton',
        'redirigir_misma_pagina',
        'posicion_contenido',
        'imagen',
        'estado'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activar_subtitulo' => 'boolean',
        'activar_boton' => 'boolean',
        'redirigir_misma_pagina' => 'boolean',
        'estado' => 'boolean',
    ];

    /**
     * Opciones disponibles para la posición del contenido
     */
    public const POSICIONES = [
        'Izquierda' => 'Izquierda',
        'Derecha' => 'Derecha'
    ];

    /**
     * Scope para elementos activos (puedes personalizar según tus necesidades)
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true); // Si decides agregar un campo 'activo'
    }

    /**
     * Obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }
        return null;
    }
}

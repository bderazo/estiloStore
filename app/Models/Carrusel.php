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
        'texto_boton',    // Asegurar que esté
        'color_boton',    // Asegurar que esté
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
     * Scope para elementos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
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

    /**
     * Accesor para el texto del botón con valor por defecto
     */
    public function getTextoBotonFormateadoAttribute()
    {
        return $this->texto_boton ?: 'Ver más';
    }

    /**
     * Accesor para el color del botón con valor por defecto
     */
    public function getColorBotonFormateadoAttribute()
    {
        return $this->color_boton ?: '#3B82F6';
    }

    /**
     * Accesor para determinar si el botón se abre en nueva pestaña
     */
    public function getBotonNuevaPestanaAttribute()
    {
        return !$this->redirigir_misma_pagina;
    }

    /**
     * Mutador para asegurar que el color tenga formato hexadecimal
     */
    public function setColorBotonAttribute($value)
    {
        // Si el valor está vacío, usar null
        if (empty($value)) {
            $this->attributes['color_boton'] = null;
            return;
        }
        
        // Asegurar que el color comience con #
        if (!str_starts_with($value, '#')) {
            $value = '#' . $value;
        }
        
        // Convertir formato #ABC a #AABBCC
        if (preg_match('/^#([A-Fa-f0-9]{3})$/', $value)) {
            $value = '#' . $value[1] . $value[1] . $value[2] . $value[2] . $value[3] . $value[3];
        }
        
        // Validar formato hexadecimal de 6 caracteres
        if (!preg_match('/^#[A-Fa-f0-9]{6}$/', $value)) {
            // Si no es válido, usar valor por defecto
            $value = '#3B82F6';
        }
        
        $this->attributes['color_boton'] = strtoupper($value);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'seccion',
        'titulo',
        'subtitulo',
        'imagen_ruta',
        'url_destino',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Secciones disponibles para banners
     */
    const SECCIONES = [
        'initial_header' => 'Header Inicial (Home)',
        'nosotros_header' => 'Header Nosotros',
        'publicidad_uno' => 'Publicidad Uno',
        'publicidad_dos' => 'Publicidad Dos',
        'panel_cuenta_uno' => 'Panel Cuenta Uno',
        'promocional_uno' => 'Promocional Uno',
        'promocional_dos' => 'Promocional Dos',
        'footer_banner' => 'Banner Footer',
    ];

    /**
     * Get the seccion label.
     */
    public function getSeccionLabel(): string
    {
        return self::SECCIONES[$this->seccion] ?? $this->seccion;
    }

    /**
     * Get the full URL for the imagen.
     */
    public function getImagenUrlAttribute(): string
    {
        if (!$this->imagen_ruta) {
            return '';
        }

        if (str_starts_with($this->imagen_ruta, 'http')) {
            return $this->imagen_ruta;
        }

        return asset('storage/' . $this->imagen_ruta);
    }
}
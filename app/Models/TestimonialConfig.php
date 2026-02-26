<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialConfig extends Model
{
    use HasFactory;

    protected $table = 'testimonial_configs';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'autoplay',
        'autoplay_speed',
        'mostrar_controles',
        'mostrar_indicadores'
    ];

    protected $casts = [
        'autoplay' => 'boolean',
        'mostrar_controles' => 'boolean',
        'mostrar_indicadores' => 'boolean',
        'autoplay_speed' => 'integer'
    ];
}
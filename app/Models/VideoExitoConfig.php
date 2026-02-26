<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoExitoConfig extends Model
{
    use HasFactory;

    protected $table = 'videos_exito_config';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'videos_por_pagina',
        'mostrar_cargar_mas'
    ];

    protected $casts = [
        'mostrar_cargar_mas' => 'boolean',
        'videos_por_pagina' => 'integer'
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model {
    protected $fillable = ['seccion', 'titulo', 'subtitulo', 'imagen_ruta', 'url_destino', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
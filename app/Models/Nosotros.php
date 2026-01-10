<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nosotros extends Model
{
    use HasFactory;

    protected $table = 'nosotros';

    protected $fillable = [
        'titulo',
        'cuerpo_principal',
        'cuerpo_secundario',
        'imagen_portada_url',
        'imagen_cuerpo_1_url',
        'imagen_cuerpo_2_url',
    ];
}

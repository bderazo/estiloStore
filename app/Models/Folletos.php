<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folletos extends Model
{
    use HasFactory;

    protected $table = 'folletos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'archivo_pdf',
        'descargas',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'descargas' => 'integer',
    ];
}
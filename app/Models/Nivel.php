<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';

    protected $fillable = [
        'nombre',
        'puntos_minimos',
        'premio_valor',
        'premio_descripcion',
        'color_hex'
    ];

    /**
     * Obtener los usuarios que pertenecen actualmente a este nivel.
     * (Relación opcional para reportes administrativos)
     */
    public function usuarios()
    {
        // Esta lógica es dinámica, pero podemos definir una relación 
        // basada en un rango si fuera necesario. Por ahora, se maneja por consulta.
        return $this->hasMany(User::class);
    }
}
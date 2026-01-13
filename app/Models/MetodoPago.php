<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodos_pago';
    protected $fillable = [
        'nombre_banco', 'tipo_pago', 'nombre_titular', 'numero_cuenta', 
        'tipo_cuenta', 'identificacion', 'instrucciones', 'imagen_qr', 'logo_banco', 'activo'
    ];
}
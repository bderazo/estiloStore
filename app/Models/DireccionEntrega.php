<?php
// app/Models/DireccionEntrega.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionEntrega extends Model
{
    protected $table = 'direcciones_entrega';
    
    protected $fillable = [
        'pedido_id',
        'barrio',
        'calle_principal',
        'calle_secundaria',
        'color_casa',
        'referencia'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación inversa con pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
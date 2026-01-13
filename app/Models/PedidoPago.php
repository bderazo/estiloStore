<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoPago extends Model
{
    protected $fillable = ['pedido_id', 'monto', 'comprobante_ruta', 'estado', 'observaciones'];

    public function pedido() {
        return $this->belongsTo(Pedido::class);
    }
}
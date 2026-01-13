<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    public $timestamps = false;
    protected $fillable = ['pedido_id', 'variante_id', 'cantidad', 'precio_unitario'];

    public function variante() {
        return $this->belongsTo(ArticuloVariante::class, 'variante_id');
    }
}
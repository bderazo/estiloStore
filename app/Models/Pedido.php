<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id', 
        'transporte_id',
        'codigo_reserva', 
        'subtotal', 
        'total', 
        'costo_envio',
        'estado'];

    public function detalles() {
        return $this->hasMany(PedidoDetalle::class);
    }

    public function pagos() {
        return $this->hasMany(PedidoPago::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    /**
     * Relación: Un pedido pertenece a un transporte (cooperativa/ruta)
     */
    public function transporte()
    {
        return $this->belongsTo(Transporte::class, 'transporte_id');
    }
}
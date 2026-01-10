<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id', 'monto', 'comprobante_url', 'estado', 'observaciones', 'tecnico_id'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    // Técnico que aprobó/rechazó
    public function tecnico() {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
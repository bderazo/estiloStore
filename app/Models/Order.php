<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total', 'puntos_generados', 'estado', 'tipo', 'fecha_limite'
    ];

    protected $casts = [
        'fecha_limite' => 'datetime',
        'total' => 'decimal:2'
    ];

    // Relación con el usuario
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relación con los productos (detalles)
    public function details() {
        return $this->hasMany(OrderDetail::class);
    }

    // Relación con los pagos/vouchers
    public function payments() {
        return $this->hasMany(OrderPayment::class);
    }
}
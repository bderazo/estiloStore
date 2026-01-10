<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id', 'articulo_id', 'articulo_variante_id', 'cantidad', 'precio_unitario'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    // Relación con la variante de tu sistema legacy
    public function variante() {
        return $this->belongsTo(ArticuloVariante::class, 'articulo_variante_id');
    }

    public function articulo() {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }
}
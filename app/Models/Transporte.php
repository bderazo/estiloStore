<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    public $timestamps = false;
    protected $fillable = ['ruta', 'precio', 'cooperativa'];
    /**
     * Relación: Un transporte puede estar en muchos pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'transporte_id');
    }
}
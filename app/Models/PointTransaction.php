<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = [
        'user_id', 
        'cantidad', 
        'motivo', 
        'reference_type', 
        'reference_id'
    ];

    /**
     * Relación con el usuario dueño de los puntos
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación polimórfica para saber qué originó el punto.
     * Puede retornar un objeto User (referido) o Order (compra).
     */
    public function reference()
    {
        return $this->morphTo();
    }
}
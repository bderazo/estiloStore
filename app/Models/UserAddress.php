<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'provincia', 'ciudad', 'direccion_exacta', 'principal'
    ];

    // Encriptación para LOPDP
    protected $casts = [
        'direccion_exacta' => 'encrypted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
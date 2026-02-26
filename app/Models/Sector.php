<?php
// app/Models/Sector.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion_principal',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relaciones
    public function direcciones(): HasMany
    {
        return $this->hasMany(DireccionEntrega::class);
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaSector::class);
    }

    // Scopes
    public function scopeByUbicacion($query, $ubicacion)
    {
        return $query->where('ubicacion_principal', $ubicacion);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
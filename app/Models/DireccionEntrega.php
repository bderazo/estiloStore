<?php
// app/Models/DireccionEntrega.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DireccionEntrega extends Model
{
    use SoftDeletes;

    protected $table = 'direcciones_entregas';

    protected $fillable = [
        'user_id',
        'pedido_id',
        'ubicacion',
        'sector_id',
        'barrio',
        'calle_principal',
        'calle_secundaria',
        'color_casa',
        'referencia',
        'instrucciones_especiales',
        'latitud',
        'longitud',
        'es_principal',
        'activo'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'activo' => 'boolean',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function entregas()
    {
        return $this->hasMany(EntregaSector::class, 'direccion_entrega_id');
    }

    // Accessors
    public function getDireccionCompletaAttribute(): string
    {
        $partes = [];
        
        if ($this->calle_principal) {
            $partes[] = "Calle Ppal: {$this->calle_principal}";
        }
        
        if ($this->calle_secundaria) {
            $partes[] = "Calle Sec: {$this->calle_secundaria}";
        }
        
        if ($this->barrio) {
            $partes[] = "Barrio: {$this->barrio}";
        }
        
        if ($this->sector) {
            $partes[] = "Sector: {$this->sector->nombre}";
        }
        
        if ($this->color_casa) {
            $partes[] = "Casa {$this->color_casa}";
        }
        
        $direccion = implode(' - ', $partes);
        
        if ($this->referencia) {
            $direccion .= " (Ref: {$this->referencia})";
        }
        
        return $direccion;
    }

    public function getResumenAttribute(): string
    {
        return "{$this->calle_principal} y {$this->calle_secundaria}, {$this->barrio} - {$this->ubicacion}";
    }
}
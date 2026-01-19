<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmpresaDato extends Model
{
    use HasFactory;

    protected $table = 'empresa_datos';

    protected $fillable = [
        'clave',
        'titulo',
        'contenido',
        'imagen',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer'
    ];

    protected $appends = [
        'clave_label',
        'imagen_url',
        'activo_label'
    ];

    public const CLAVES = [
        'quienes_somos' => 'Quiénes Somos',
        'vision' => 'Visión',
        'mision' => 'Misión',
        'valores' => 'Valores',
        'equipo' => 'Nuestro Equipo',
        'contacto_info' => 'Información de Contacto'
    ];

    public static function getClaves()
    {
        return self::CLAVES;
    }

    public function getClaveLabelAttribute()
    {
        return self::CLAVES[$this->clave] ?? $this->clave;
    }

    public function getImagenUrlAttribute(): string
    {
        if (!$this->imagen) {
            return '';
        }

        if (str_starts_with($this->imagen, 'http')) {
            return $this->imagen;
        }

        return asset('storage/' . $this->imagen);
    }

    public function getActivoLabelAttribute()
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('titulo', 'like', "%{$search}%")
              ->orWhere('contenido', 'like', "%{$search}%")
              ->orWhere('clave', 'like', "%{$search}%");
        });
    }

    public function scopeFiltrarPorEstado($query, $estado)
    {
        if ($estado !== null) {
            return $query->where('activo', $estado);
        }
        return $query;
    }
}
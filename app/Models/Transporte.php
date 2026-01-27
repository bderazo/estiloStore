<?php
// app/Models/Transporte.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transporte extends Model
{
    protected $table = 'transportes';
    
    protected $fillable = [
        'ruta',
        'precio',
        'cooperativa',
        'estado',
        'tiempo_estimado'
    ];
    
    protected $casts = [
        'precio' => 'decimal:2',
        'estado' => 'boolean',
        'tiempo_estimado' => 'decimal:2'
    ];
    
    protected $appends = [
        'precio_formateado',
        'estado_label',
        'tiempo_estimado_formateado'
    ];
    
    /**
     * Scope para búsqueda por ruta
     */
    public function scopeBuscarPorRuta(Builder $query, string $ruta): Builder
    {
        return $query->where('ruta', 'like', "%{$ruta}%");
    }
    
    /**
     * Scope para búsqueda por cooperativa
     */
    public function scopeBuscarPorCooperativa(Builder $query, string $cooperativa): Builder
    {
        return $query->where('cooperativa', 'like', "%{$cooperativa}%");
    }
    
    /**
     * Scope para ordenar por precio
     */
    public function scopeOrdenarPorPrecio(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('precio', $direction);
    }
    
    /**
     * Scope para rutas activas
     */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', true);
    }
    
    /**
     * Attribute: precio formateado
     */
    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio, 2);
    }
    
    /**
     * Attribute: estado label
     */
    public function getEstadoLabelAttribute(): string
    {
        return $this->estado ? 'Activo' : 'Inactivo';
    }
    
    /**
     * Attribute: tiempo estimado formateado
     */
    public function getTiempoEstimadoFormateadoAttribute(): ?string
    {
        if (!$this->tiempo_estimado) {
            return null;
        }
        
        $horas = floor($this->tiempo_estimado);
        $minutos = ($this->tiempo_estimado - $horas) * 60;
        
        if ($minutos > 0) {
            return "{$horas}h {$minutos}m";
        }
        
        return "{$horas} horas";
    }
    
    /**
     * Obtener cooperativas únicas
     */
    public static function obtenerCooperativas(): array
    {
        return self::select('cooperativa')
            ->distinct()
            ->orderBy('cooperativa')
            ->pluck('cooperativa')
            ->toArray();
    }
    
    /**
     * Obtener estadísticas
     */
    public static function obtenerEstadisticas(): array
    {
        return [
            'total' => self::count(),
            'activos' => self::where('estado', true)->count(),
            'inactivos' => self::where('estado', false)->count(),
            'precio_promedio' => self::avg('precio'),
            'cooperativas_unicas' => self::distinct('cooperativa')->count(),
            'tiempo_promedio' => self::avg('tiempo_estimado'),
        ];
    }
}
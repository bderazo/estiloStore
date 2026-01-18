<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Folleto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'archivo_pdf',
        'estado',
        'descargas'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'descargas' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Incrementar contador de descargas
     */
    public function incrementarDescargas()
    {
        $this->increment('descargas');
        return $this;
    }

    /**
     * Obtener URL completa del archivo
     */
    public function getArchivoUrlAttribute()
    {
        return Storage::url($this->archivo_pdf);
    }

    /**
     * Obtener tamaño del archivo formateado
     */
    public function getTamanoArchivoAttribute()
    {
        if (!Storage::exists($this->archivo_pdf)) {
            return '0 KB';
        }

        $bytes = Storage::size($this->archivo_pdf);
        
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        
        return round($bytes / 1024, 2) . ' KB';
    }

    /**
     * Obtener etiqueta del estado
     */
    public function getEstadoLabelAttribute()
    {
        return $this->estado ? 'Activo' : 'Inactivo';
    }

    /**
     * Obtener fecha formateada
     */
    public function getFechaCreacionFormateadaAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Scope para folletos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Scope para buscar por nombre o descripción
     */
    public function scopeBuscar($query, $search)
    {
        return $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
    }

    /**
     * Eliminar archivo físico al eliminar registro
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($folleto) {
            if (Storage::exists($folleto->archivo_pdf)) {
                Storage::delete($folleto->archivo_pdf);
            }
        });
    }
}
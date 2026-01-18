<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class MetodoPago extends Model
{
    protected $table = 'metodos_pago';
    
    protected $fillable = [
        'nombre_banco',
        'tipo_pago',
        'nombre_titular',
        'numero_cuenta',
        'tipo_cuenta',
        'identificacion',
        'instrucciones',
        'imagen_qr',
        'logo_banco',
        'activo',
    ];
    
    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Los atributos que se agregarán al array/JSON
     */
    protected $appends = [
        'imagen_qr_url',
        'logo_banco_url', 
        'tipo_pago_label',
        'cuenta_formateada'
    ];
    
    /**
     * Scope para métodos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar por fecha de creación
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    
    /**
     * Scope para filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        if ($tipo) {
            return $query->where('tipo_pago', $tipo);
        }
        return $query;
    }
    
    /**
     * Atributo: URL completa de imagen QR
     */
    protected function imagenQrUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->imagen_qr)) {
                    return null;
                }
                return Storage::url($this->imagen_qr);
            }
        );
    }
    
    /**
     * Atributo: URL completa de logo banco
     */
    protected function logoBancoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->logo_banco)) {
                    return null;
                }
                return Storage::url($this->logo_banco);
            }
        );
    }
    
    /**
     * Atributo: Etiqueta del tipo de pago
     */
    protected function tipoPagoLabel(): Attribute
    {
        $labels = [
            'Transferencia' => 'Transferencia Bancaria',
            'QR' => 'Pago QR',
            'Efectivo' => 'Efectivo',
            'Otro' => 'Otro Método'
        ];
        
        return Attribute::make(
            get: fn () => $labels[$this->tipo_pago] ?? $this->tipo_pago
        );
    }
    
    /**
     * Atributo: Número de cuenta formateado
     */
    protected function cuentaFormateada(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->numero_cuenta) {
                    return null;
                }
                
                // Mostrar solo últimos 4 dígitos para seguridad
                $length = strlen($this->numero_cuenta);
                if ($length > 4) {
                    return '****' . substr($this->numero_cuenta, -4);
                }
                
                return $this->numero_cuenta;
            }
        );
    }
    
    /**
     * Atributo: Número de cuenta completo (solo para administradores)
     */
    protected function numeroCuentaCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->numero_cuenta
        );
    }
}
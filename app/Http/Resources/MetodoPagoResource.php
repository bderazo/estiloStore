<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetodoPagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre_banco' => $this->nombre_banco,
            'tipo_pago' => $this->tipo_pago,
            'tipo_pago_label' => $this->tipo_pago,
            'nombre_titular' => $this->nombre_titular,
            'numero_cuenta' => $this->numero_cuenta,
            'tipo_cuenta' => $this->tipo_cuenta,
            'identificacion' => $this->identificacion,
            'instrucciones' => $this->instrucciones,
            'activo' => $this->activo,
            'logo_banco' => $this->logo_banco,
            'imagen_qr' => $this->imagen_qr,
            'logo_banco_url' => $this->logo_banco ? asset('storage/' . $this->logo_banco) : null,
            'imagen_qr_url' => $this->imagen_qr ? asset('storage/' . $this->imagen_qr) : null,
            'cuenta_formateada' => $this->cuenta_formateada,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
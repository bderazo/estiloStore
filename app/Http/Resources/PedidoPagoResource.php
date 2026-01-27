<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PedidoPagoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'monto' => $this->monto,
            'comprobante_ruta' => $this->comprobante_ruta,
            'estado' => $this->estado,
            'observacion' => $this->observacion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
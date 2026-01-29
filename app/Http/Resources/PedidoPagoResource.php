<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PedidoPagoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'monto' => (float) $this->monto,
            'comprobante_ruta' => $this->comprobante_ruta,
            'estado' => $this->estado,
            'observacion' => $this->observacion,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            'pedido' => $this->whenLoaded('pedido', function () {
                return [
                    'id' => $this->pedido->id,
                    'codigo_reserva' => $this->pedido->codigo_reserva,
                    'total' => (float) $this->pedido->total
                ];
            })
        ];
    }
}
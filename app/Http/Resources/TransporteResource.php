<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransporteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ruta' => $this->ruta,
            'precio' => $this->precio,
            'precio_formateado' => $this->precio_formateado,
            'cooperativa' => $this->cooperativa,
            'estado' => $this->estado,
            'estado_label' => $this->estado_label,
            'tiempo_estimado' => $this->tiempo_estimado,
            'tiempo_estimado_formateado' => $this->tiempo_estimado_formateado,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
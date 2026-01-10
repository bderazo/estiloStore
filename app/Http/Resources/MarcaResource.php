<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarcaResource extends JsonResource
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
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'activo' => (bool) $this->activo,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            // Información adicional que puede ser útil
            'formatted_created_at' => $this->created_at?->format('d/m/Y H:i'),
            'formatted_updated_at' => $this->updated_at?->format('d/m/Y H:i'),
            'estado_texto' => $this->activo ? 'Activo' : 'Inactivo',
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TallaResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'activo' => $this->activo,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),

            // Información adicional
            'nombre_completo' => $this->descripcion
                ? "{$this->nombre} - {$this->descripcion}"
                : $this->nombre,

            'estado_texto' => $this->activo ? 'Activo' : 'Inactivo',

            // Para badges y UI
            'badge_class' => $this->activo
                ? 'badge-outline-success'
                : 'badge-outline-danger',

            // Información de fechas formateadas
            'created_at_formatted' => $this->created_at->format('d/m/Y'),
            'updated_at_formatted' => $this->updated_at->format('d/m/Y H:i'),

            // Tiempo transcurrido
            'created_ago' => $this->created_at->diffForHumans(),
            'updated_ago' => $this->updated_at->diffForHumans(),

            // Para búsquedas y filtros
            'searchable_text' => strtolower($this->nombre . ' ' . ($this->descripcion ?? '')),

            // Longitudes para validaciones frontend
            'nombre_length' => strlen($this->nombre),
            'descripcion_length' => strlen($this->descripcion ?? ''),
        ];
    }
}

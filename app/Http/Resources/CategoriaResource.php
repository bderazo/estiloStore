<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // URL completa para im芍genes y logos
        $imagenUrl = $this->imagen ? asset('storage/' . $this->imagen) : null;
        $logoUrl = $this->logo ? asset('storage/' . $this->logo) : null;

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'imagen' => $this->imagen,
            'imagen_url' => $imagenUrl, // URL completa
            'logo' => $this->logo, // AGREGAR
            'logo_url' => $logoUrl, // AGREGAR URL completa
            'descripcion' => $this->descripcion,
            'parent_id' => $this->parent_id,
            'activo' => (bool) $this->activo,
            'orden' => $this->orden,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            // Relaciones
            'parent' => $this->whenLoaded('parent', function () {
                return $this->parent ? new self($this->parent) : null;
            }),
            'children' => self::collection($this->whenLoaded('children')),
            'children_count' => $this->children_count ?? 0,

            // Informaci車n adicional
            'has_children' => $this->when(
                $this->relationLoaded('children'),
                $this->children->count() > 0
            ),
            'level' => $this->level,
            'path' => $this->path,
        ];
    }
}
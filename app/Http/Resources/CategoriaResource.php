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
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'imagen' => $this->imagen,
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

            // Información adicional
            'has_children' => $this->when(
                $this->relationLoaded('children'),
                $this->children->count() > 0
            ),
            'level' => $this->level,
            'path' => $this->path,
        ];
    }
}

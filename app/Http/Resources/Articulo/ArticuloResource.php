<?php

namespace App\Http\Resources\Articulo;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticuloResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'especificaciones' => $this->especificaciones,
            'precio' => $this->precio,
            'sku' => $this->sku,
            'categoria' => $this->whenLoaded('categoria', function () {
                return [
                    'id' => $this->categoria->id,
                    'nombre' => $this->categoria->nombre,
                ];
            }),
            'marca' => $this->whenLoaded('marca', function () {
                return [
                    'id' => $this->marca->id,
                    'nombre' => $this->marca->nombre,
                ];
            }),
            'activo' => $this->activo,
            'destacado' => $this->destacado,
            'imagenes' => ArticuloImagenResource::collection($this->whenLoaded('imagenes')),
            'variantes' => ArticuloVarianteResource::collection($this->whenLoaded('variantes')),
            'stock_total' => $this->when($this->relationLoaded('variantes'), $this->stock_total),
            'stock_disponible' => $this->when($this->relationLoaded('variantes'), $this->stock_disponible),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

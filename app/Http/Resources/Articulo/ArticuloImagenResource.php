<?php

namespace App\Http\Resources\Articulo;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticuloImagenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'articulo_id' => $this->articulo_id,
            'ruta' => $this->ruta,
            'url' => asset('storage/' . $this->ruta),
            'orden' => $this->orden,
            'metadatos' => $this->metadatos,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

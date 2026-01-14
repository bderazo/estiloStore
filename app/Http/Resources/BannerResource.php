<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'seccion' => $this->seccion,
            'seccion_label' => $this->getSeccionLabel(),
            'titulo' => $this->titulo,
            'subtitulo' => $this->subtitulo,
            'imagen_ruta' => $this->imagen_ruta,
            'imagen_url' => $this->imagen_url,
            'url_destino' => $this->url_destino,
            'estado' => $this->estado,
            'estado_label' => $this->estado ? 'Activo' : 'Inactivo',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarruselResource extends JsonResource
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
            'titulo' => $this->titulo,
            'subtitulo' => $this->subtitulo,
            'activar_subtitulo' => (bool) $this->activar_subtitulo,
            'activar_boton' => (bool) $this->activar_boton,
            'url_boton' => $this->url_boton,
            'redirigir_misma_pagina' => (bool) $this->redirigir_misma_pagina,
            'posicion_contenido' => $this->posicion_contenido,
            'imagen' => $this->imagen,
            'imagen_url' => $this->imagen_url, // Usar el accessor del modelo
            'estado' => (bool) $this->estado,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

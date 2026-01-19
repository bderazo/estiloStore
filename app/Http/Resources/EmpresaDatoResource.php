<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaDatoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'clave' => $this->clave,
            'clave_label' => $this->clave_label,
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'imagen' => $this->imagen,
            'imagen_url' => $this->imagen_url,
            'activo' => $this->activo,
            'activo_label' => $this->activo_label,
            'orden' => $this->orden,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
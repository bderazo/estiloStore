<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolletoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'archivo_pdf' => $this->archivo_pdf,
            'archivo_url' => $this->archivo_url,
            'tamano_archivo' => $this->tamano_archivo,
            'descargas' => $this->descargas,
            'estado' => $this->estado,
            'estado_label' => $this->estado_label,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fecha_creacion_formateada' => $this->fecha_creacion_formateada,
        ];
    }
}
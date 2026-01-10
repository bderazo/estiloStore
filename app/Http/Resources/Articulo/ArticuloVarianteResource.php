<?php

namespace App\Http\Resources\Articulo;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticuloVarianteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'articulo_id' => $this->articulo_id,
            'atributos' => $this->atributos,
            'atributos_formateados' => $this->formatearAtributos(),
            'sku' => $this->sku,
            'precio' => $this->precio,
            'precio_final' => $this->precio_final,
            'stock' => $this->stock,
            'reserved' => $this->reserved,
            'disponible' => $this->available,
            'activo' => $this->activo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Formatear atributos para presentación
     */
    protected function formatearAtributos(): array
    {
        $atributos = $this->atributos ?? [];
        $formateados = [];

        foreach ($atributos as $tipo => $id) {
            $formateados[$tipo] = [
                'id' => $id,
                // Aquí se podrían cargar los valores desde BD si es necesario
            ];
        }

        return $formateados;
    }
}

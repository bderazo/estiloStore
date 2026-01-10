<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
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
            'codigo_hex' => $this->codigo_hex,
            'activo' => $this->activo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Campos formateados
            'formatted_created_at' => $this->created_at?->format('d/m/Y H:i'),
            'formatted_updated_at' => $this->updated_at?->format('d/m/Y H:i'),
            'estado_texto' => $this->activo ? 'Activo' : 'Inactivo',
            'color_preview' => [
                'background' => $this->codigo_hex,
                'contrast' => $this->getContrastColor($this->codigo_hex),
                'luminance' => $this->getLuminance($this->codigo_hex)
            ]
        ];
    }

    /**
     * Obtener color de contraste para el texto (blanco o negro)
     */
    private function getContrastColor(string $hexColor): string
    {
        // Remover el #
        $hex = str_replace('#', '', $hexColor);

        // Convertir a RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calcular luminancia
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Retornar blanco o negro según la luminancia
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }

    /**
     * Calcular la luminancia del color
     */
    private function getLuminance(string $hexColor): float
    {
        // Remover el #
        $hex = str_replace('#', '', $hexColor);

        // Convertir a RGB
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        // Aplicar corrección gamma
        $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        // Calcular luminancia relativa
        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}

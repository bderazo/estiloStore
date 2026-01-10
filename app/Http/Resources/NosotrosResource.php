<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class NosotrosResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'cuerpo_principal' => $this->cuerpo_principal,
            'cuerpo_secundario' => $this->cuerpo_secundario,
            'imagen_portada_url' => $this->resolveMediaUrl($this->imagen_portada_url),
            'imagen_cuerpo_1_url' => $this->resolveMediaUrl($this->imagen_cuerpo_1_url),
            'imagen_cuerpo_2_url' => $this->resolveMediaUrl($this->imagen_cuerpo_2_url),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
            'formatted_created_at' => optional($this->created_at)->format('d/m/Y H:i'),
            'formatted_updated_at' => optional($this->updated_at)->format('d/m/Y H:i'),
            'slug' => Str::slug($this->titulo ?? ''),
        ];
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}

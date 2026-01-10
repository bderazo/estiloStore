<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCarouselResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $variants = $this->resolveImageVariants();

        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'slug' => Str::slug($this->titulo ?? (string) $this->id),
            'alt_text' => $this->resolveAltText(),
          
            'subtitulo' => $this->subtitulo,
            'boton' => [
                'visible' => (bool) $this->activar_boton,
                'texto' => $this->activar_boton ? (data_get($this, 'boton_texto') ?? __('Ver más')) : null,
                'url' => $this->url_boton,
                'nueva_pestana' => ! $this->redirigir_misma_pagina,
            ],
            'posicion_contenido' => $this->posicion_contenido,
            'imagenes' => $variants,
        ];
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    protected function resolveImageVariants(): array
    {
        if (! $this->imagen) {
            return [
                'webp' => ['sm' => null, 'md' => null, 'lg' => null],
                'fallback' => ['sm' => null, 'md' => null, 'lg' => null],
            ];
        }

        $disk = Storage::disk('public');
        $basePath = $this->imagen;
        $extension = pathinfo($basePath, PATHINFO_EXTENSION);
        $baseName = Str::beforeLast($basePath, '.' . $extension);

        $sizes = ['sm', 'md', 'lg'];

        $variants = [
            'webp' => [],
            'fallback' => [],
        ];

        $makeUrl = static fn (?string $path): ?string => $path ? asset('storage/' . ltrim($path, '/')) : null;

        foreach ($sizes as $size) {
            $webpPath = $baseName . '-' . $size . '.webp';
            $fallbackPath = $baseName . '-' . $size . '.' . $extension;

            $variants['webp'][$size] = $disk->exists($webpPath)
                ? $makeUrl($webpPath)
                : ($disk->exists($basePath) ? $makeUrl($basePath) : null);

            $variants['fallback'][$size] = $disk->exists($fallbackPath)
                ? $makeUrl($fallbackPath)
                : ($disk->exists($basePath) ? $makeUrl($basePath) : null);
        }

        return $variants;
    }

    protected function resolveAltText(): string
    {
        if (! empty($this->alt_text)) {
            return $this->alt_text;
        }

        if (! empty($this->titulo)) {
            return strip_tags($this->titulo);
        }

        if (! empty($this->subtitulo)) {
            return strip_tags($this->subtitulo);
        }

        return __('Banner promocional');
    }
}

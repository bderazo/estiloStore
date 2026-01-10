<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class BannerService {
    
    /**
     * Carga o actualiza un banner de sección
     */
    public function upsertBanner(array $data, UploadedFile $file) {
        
        // 1. Validar peso (2MB es lo ideal para web, 5MB máximo)
        $validator = Validator::make(['file' => $file], [
            'file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // 2048 KB = 2MB
        ]);

        if ($validator->fails()) {
            throw new Exception("Archivo inválido: " . $validator->errors()->first());
        }

        // 2. Buscar si ya existe banner para esa sección para reemplazar la imagen
        $bannerExistente = Banner::where('seccion', $data['seccion'])->first();

        if ($bannerExistente && Storage::disk('public')->exists($bannerExistente->imagen_ruta)) {
            Storage::disk('public')->delete($bannerExistente->imagen_ruta);
        }

        // 3. Guardar nueva imagen
        $rutaFisica = $file->store('banners', 'public');

        // 4. Guardar en BD (updateOrCreate busca por seccion)
        return Banner::updateOrCreate(
            ['seccion' => $data['seccion']],
            [
                'titulo'      => $data['titulo'] ?? null,
                'subtitulo'   => $data['subtitulo'] ?? null,
                'imagen_ruta' => $rutaFisica,
                'url_destino' => $data['url_destino'] ?? null,
                'estado'      => $data['estado'] ?? true,
            ]
        );
    }

    /**
     * Obtener imagen por sección
     */
    public function getBannerBySeccion(string $seccion) {
        return Banner::where('seccion', $seccion)->where('estado', true)->first();
    }
}
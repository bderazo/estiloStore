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
     * Obtener banner por sección (SOLO activos)
     */
    public function getBannerBySeccion(string $seccion) {
        return Banner::where('seccion', $seccion)
            ->where('estado', true)
            ->first();
    }

    /**
     * 👇 NUEVO: Obtener banner por sección SIN filtrar por estado (para depuración)
     */
    public function getBannerBySeccionDebug(string $seccion) {
        $banner = Banner::where('seccion', $seccion)->first();
        
        if (!$banner) {
            return [
                'existe' => false,
                'mensaje' => "No existe ningún banner para la sección '{$seccion}'",
                'seccion' => $seccion
            ];
        }
        
        return [
            'existe' => true,
            'id' => $banner->id,
            'seccion' => $banner->seccion,
            'titulo' => $banner->titulo,
            'subtitulo' => $banner->subtitulo,
            'imagen_ruta' => $banner->imagen_ruta,
            'estado' => $banner->estado,
            'estado_texto' => $banner->estado ? 'ACTIVO' : 'INACTIVO',
            'url_destino' => $banner->url_destino,
            'created_at' => $banner->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $banner->updated_at->format('Y-m-d H:i:s'),
            'imagen_url' => $banner->imagen_ruta ? asset('storage/' . $banner->imagen_ruta) : null,
            'imagen_existe_en_storage' => $banner->imagen_ruta ? Storage::disk('public')->exists($banner->imagen_ruta) : false
        ];
    }

    /**
     * 👇 NUEVO: Listar todas las secciones disponibles
     */
    public function listarSecciones() {
        return Banner::select('seccion', 'estado')
            ->distinct()
            ->get()
            ->map(function($banner) {
                return [
                    'seccion' => $banner->seccion,
                    'estado' => $banner->estado,
                    'estado_texto' => $banner->estado ? 'ACTIVO' : 'INACTIVO'
                ];
            });
    }
}
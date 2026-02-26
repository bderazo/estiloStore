<?php

namespace App\Services;

use App\Models\Testimonial;
use App\Models\TestimonialConfig;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestimonialService
{
    /**
     * Obtener testimonios activos para la vista
     */
    public function getTestimoniosActivos()
    {
        return Testimonial::activos()
            ->ordenados()
            ->get()
            ->map(function ($testimonial) {
                return [
                    'id' => $testimonial->id,
                    'nombre' => $testimonial->nombre,
                    'cargo' => $testimonial->cargo,
                    'testimonio' => $testimonial->testimonio,
                    'rating' => $testimonial->rating,
                    'imagen_url' => $testimonial->imagen_url,
                    'orden' => $testimonial->orden
                ];
            });
    }

    /**
     * Obtener configuración del carrusel
     */
    public function getConfiguracion()
    {
        $config = TestimonialConfig::first();
        
        if (!$config) {
            // Crear configuración por defecto
            $config = TestimonialConfig::create([
                'titulo' => 'Testimonios de Clientes',
                'subtitulo' => 'Lo que dicen nuestras clientas',
                'autoplay' => true,
                'autoplay_speed' => 5000,
                'mostrar_controles' => true,
                'mostrar_indicadores' => true
            ]);
        }

        return $config;
    }

    /**
     * Actualizar configuración
     */
    public function actualizarConfiguracion(array $datos)
    {
        $config = TestimonialConfig::first();
        
        if (!$config) {
            $config = new TestimonialConfig();
        }

        $config->fill($datos);
        $config->save();

        return $config;
    }

    /**
     * Subir imagen de testimonio
     */
    public function subirImagen($file, $testimonioId = null)
    {
        try {
            $path = $file->store('testimonials', 'public');
            
            Log::info('Imagen subida:', [
                'path' => $path,
                'url' => asset('storage/' . $path)
            ]);
            
            return $path;
        } catch (\Exception $e) {
            Log::error('Error subiendo imagen:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Eliminar imagen
     */
    public function eliminarImagen($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
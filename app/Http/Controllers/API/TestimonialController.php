<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialConfig;
use App\Services\TestimonialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    protected $testimonialService;

    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    /**
     * Listado de testimonios
     */
    public function index()
    {
        $testimonials = Testimonial::orderBy('orden')
            ->orderBy('id')
            ->get();

        $config = $this->testimonialService->getConfiguracion();

        return view('admin.testimonials.index', compact('testimonials', 'config'));
    }

    /**
     * API para obtener testimonios (Vue)
     */
    public function getTestimonials()
    {
        $testimonials = Testimonial::orderBy('orden')
            ->orderBy('id')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'nombre' => $t->nombre,
                    'cargo' => $t->cargo,
                    'testimonio' => $t->testimonio,
                    'rating' => $t->rating,
                    'imagen' => $t->imagen ? asset('storage/' . $t->imagen) : null,
                    'orden' => $t->orden,
                    'activo' => $t->activo
                ];
            });

        return response()->json($testimonials);
    }

    /**
     * Guardar testimonio
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateTestimonio($request);

            if ($request->hasFile('imagen')) {
                $validated['imagen'] = $this->testimonialService->subirImagen($request->file('imagen'));
            }

            $testimonial = Testimonial::create($validated);

            Log::info('Testimonio creado:', ['id' => $testimonial->id]);

            return response()->json([
                'success' => true,
                'message' => 'Testimonio creado exitosamente',
                'data' => $testimonial
            ]);
        } catch (\Exception $e) {
            Log::error('Error creando testimonio:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear testimonio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar testimonio
     */
    public function update(Request $request, $id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            $validated = $this->validateTestimonio($request, $id);

            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior
                if ($testimonial->imagen) {
                    $this->testimonialService->eliminarImagen($testimonial->imagen);
                }
                $validated['imagen'] = $this->testimonialService->subirImagen($request->file('imagen'));
            }

            $testimonial->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Testimonio actualizado exitosamente',
                'data' => $testimonial
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar testimonio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar testimonio
     */
    public function destroy($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);

            if ($testimonial->imagen) {
                $this->testimonialService->eliminarImagen($testimonial->imagen);
            }

            $testimonial->delete();

            return response()->json([
                'success' => true,
                'message' => 'Testimonio eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar testimonio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar estado del testimonio
     */
    public function toggleStatus($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            $testimonial->activo = !$testimonial->activo;
            $testimonial->save();

            return response()->json([
                'success' => true,
                'activo' => $testimonial->activo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado'
            ], 500);
        }
    }

    /**
     * Guardar configuración del carrusel
     */
    public function saveConfig(Request $request)
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'subtitulo' => 'nullable|string|max:255',
                'autoplay' => 'boolean',
                'autoplay_speed' => 'integer|min:1000|max:10000',
                'mostrar_controles' => 'boolean',
                'mostrar_indicadores' => 'boolean'
            ]);

            $config = $this->testimonialService->actualizarConfiguracion($validated);

            return response()->json([
                'success' => true,
                'message' => 'Configuración guardada',
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar configuración'
            ], 500);
        }
    }

    /**
     * Validar testimonio
     */
    private function validateTestimonio(Request $request, $id = null)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'testimonio' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
            'orden' => 'required|integer|min:0',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|max:2048'
        ];

        return $request->validate($rules);
    }

    public function getConfig()
    {
        try {
            $config = $this->testimonialService->getConfiguracion();

            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener configuración'
            ], 500);
        }
    }
}
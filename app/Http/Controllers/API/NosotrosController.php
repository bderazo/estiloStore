<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateNosotrosRequest;
use App\Http\Resources\NosotrosResource;
use App\Services\NosotrosService;
use Illuminate\Http\JsonResponse;

class NosotrosController extends Controller
{
    public function __construct(private readonly NosotrosService $service)
    {
    }

    public function show(): JsonResponse
    {
        try {
            $nosotros = $this->service->getSingleton();

            return response()->json([
                'success' => true,
                'message' => $nosotros ? 'Información obtenida correctamente' : 'No hay información registrada',
                'data' => $nosotros ? new NosotrosResource($nosotros) : null,
                'errors' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la información de Nosotros',
                'data' => null,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(UpdateNosotrosRequest $request): JsonResponse
    {
        return $this->persist($request);
    }

    public function update(UpdateNosotrosRequest $request): JsonResponse
    {
        return $this->persist($request);
    }

    protected function persist(UpdateNosotrosRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $imageFields = [
                'imagen_portada_url',
                'imagen_cuerpo_1_url',
                'imagen_cuerpo_2_url',
            ];

            $files = [];
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    $files[$field] = $request->file($field);
                    unset($validated[$field]);
                } elseif (array_key_exists($field, $validated) && $validated[$field] === '') {
                    $validated[$field] = null;
                }
            }

            $nosotros = $this->service->updateOrCreate($validated, $files);

            return response()->json([
                'success' => true,
                'message' => 'Información actualizada correctamente',
                'data' => new NosotrosResource($nosotros),
                'errors' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la información de Nosotros',
                'data' => null,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Services;

use App\Models\Articulo;
use App\Models\ArticuloImagen;
use App\Models\ArticuloVariante;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticuloService
{

    protected $articulo;
    public function __construct(Articulo $articulo)
    {
        $this->articulo = $articulo;
    }
    /**
     * Obtener artículos con filtros
     */
    public function getArticulos(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Articulo::query();

        // Búsqueda por nombre, descripción o SKU
        if (!empty($filters['q'])) {
            $query->search($filters['q']);
        }

        // Filtro por categoría
        if (!empty($filters['categoria_id'])) {
            $query->where('categoria_id', $filters['categoria_id']);
        }

        // Filtro por marca
        if (!empty($filters['marca_id'])) {
            $query->where('marca_id', $filters['marca_id']);
        }

        // Filtro por estado activo
        if (isset($filters['activo'])) {
            $query->where('activo', (bool) $filters['activo']);
        }

        // Filtro por destacado
        if (isset($filters['destacado'])) {
            $query->where('destacado', (bool) $filters['destacado']);
        }

        // Filtro por rango de precio
        if (!empty($filters['precio_min']) && !empty($filters['precio_max'])) {
            $query->precioEntre($filters['precio_min'], $filters['precio_max']);
        }

        // Cargar relaciones
        $query->with(['categoria', 'marca', 'imagenes', 'variantes']);

        // Ordenar
        $ordenarPor = $filters['orden_por'] ?? 'created_at';
        $direccion = $filters['direccion'] ?? 'desc';
        $query->orderBy($ordenarPor, $direccion);

        return $query->paginate($perPage);
    }

    /**
     * Obtener artículo por ID con relaciones
     */
    public function getArticulo(int $id): ?Articulo
    {
        return Articulo::with(['categoria', 'marca', 'imagenes', 'variantes'])->find($id);
    }

    /**
     * Crear artículo con variantes e imágenes (transaccional)
     */
    public function crearArticulo(array $datos): Articulo
    {
        return DB::transaction(function () use ($datos) {
            // 1. Crear artículo base
            $articulo = Articulo::create([
                'nombre' => $datos['nombre'],
                'slug' => $datos['slug'],
                'descripcion' => $datos['descripcion'] ?? null,
                'especificaciones' => $datos['especificaciones'] ?? null,
                'precio' => $datos['precio'],
                'sku' => $datos['sku'] ?? null,
                'categoria_id' => $datos['categoria_id'] ?? null,
                'marca_id' => $datos['marca_id'] ?? null,
                'activo' => $datos['activo'] ?? true,
                'destacado' => $datos['destacado'] ?? false,
            ]);

            // 2. Crear variantes (solo atributos y stock)
            if (!empty($datos['variantes'])) {
                foreach ($datos['variantes'] as $variante) {
                    $articulo->variantes()->create([
                        'atributos' => $variante['atributos'],
                        'stock' => $variante['stock'] ?? 0,
                        'activo' => $variante['activo'] ?? true,
                    ]);
                }
            }

            // 3. Subir imágenes
            if (!empty($datos['imagenes'])) {
                $this->guardarImagenes($articulo, $datos['imagenes']);
            }

            return $articulo->load(['categoria', 'marca', 'imagenes', 'variantes']);
        });
    }

    /**
     * Actualizar artículo (transaccional)
     */
    public function actualizarArticulo(Articulo $articulo, array $datos): Articulo
    {
        return DB::transaction(function () use ($articulo, $datos) {
            // 1. Actualizar datos base
            $articulo->update([
                'nombre' => $datos['nombre'] ?? $articulo->nombre,
                'slug' => $datos['slug'] ?? $articulo->slug,
                'descripcion' => $datos['descripcion'] ?? $articulo->descripcion,
                'especificaciones' => $datos['especificaciones'] ?? $articulo->especificaciones,
                'precio' => $datos['precio'] ?? $articulo->precio,
                'sku' => $datos['sku'] ?? $articulo->sku,
                'categoria_id' => $datos['categoria_id'] ?? $articulo->categoria_id,
                'marca_id' => $datos['marca_id'] ?? $articulo->marca_id,
                'activo' => $datos['activo'] ?? $articulo->activo,
                'destacado' => $datos['destacado'] ?? $articulo->destacado,
            ]);

            // 2. Manejar variantes con sincronización completa
            Log::info('=== PROCESANDO VARIANTES EN SERVICIO ===');
            Log::info('Variantes a eliminar: ', $datos['variantes_eliminar'] ?? []);
            Log::info('Variantes a procesar: ', $datos['variantes'] ?? []);

            // 2.1. Eliminar variantes marcadas para eliminación
            if (!empty($datos['variantes_eliminar'])) {
                Log::info('Eliminando variantes marcadas: ', $datos['variantes_eliminar']);
                ArticuloVariante::whereIn('id', $datos['variantes_eliminar'])->delete();
            }

            // 2.2. Obtener IDs de variantes que se mantienen o actualizan
            $idsVariantesEnviadas = [];
            if (!empty($datos['variantes'])) {
                foreach ($datos['variantes'] as $variante) {
                    if (isset($variante['id']) && $variante['id']) {
                        $idsVariantesEnviadas[] = $variante['id'];
                    }
                }
            }

            Log::info('IDs de variantes enviadas: ', $idsVariantesEnviadas);

            // 2.3. Eliminar variantes huérfanas (que ya no están en la lista y no están marcadas para eliminar)
            $variantesHuerfanas = $articulo->variantes()
                ->whereNotIn('id', array_merge($idsVariantesEnviadas, $datos['variantes_eliminar'] ?? []))
                ->get();

            if ($variantesHuerfanas->count() > 0) {
                Log::info('Eliminando variantes huérfanas: ', $variantesHuerfanas->pluck('id')->toArray());
                $variantesHuerfanas->each->delete();
            }

            // 2.4. Procesar variantes enviadas (actualizar existentes y crear nuevas)
            if (!empty($datos['variantes'])) {
                foreach ($datos['variantes'] as $index => $variante) {
                    Log::info("Procesando variante {$index}: ", $variante);

                    if (isset($variante['id']) && $variante['id']) {
                        // Actualizar existente
                        Log::info("Actualizando variante existente ID: {$variante['id']}");
                        $v = $articulo->variantes()->find($variante['id']);
                        if ($v) {
                            $v->update([
                                'atributos' => $variante['atributos'] ?? $v->atributos,
                                'stock' => $variante['stock'] ?? $v->stock,
                                'activo' => $variante['activo'] ?? $v->activo,
                            ]);
                            Log::info("Variante actualizada correctamente");
                        } else {
                            Log::warning("No se encontró variante con ID: {$variante['id']} - posiblemente ya eliminada");
                        }
                    } else {
                        // Crear nueva
                        Log::info("Creando nueva variante (sin ID)");
                        $nueva = $articulo->variantes()->create([
                            'atributos' => $variante['atributos'],
                            'stock' => $variante['stock'] ?? 0,
                            'activo' => $variante['activo'] ?? true,
                        ]);
                        Log::info("Nueva variante creada con ID: {$nueva->id}");
                    }
                }
            }

            // 3. Manejar imágenes
            if (!empty($datos['imagenes_eliminar'])) {
                $this->eliminarImagenes($datos['imagenes_eliminar']);
            }

            if (!empty($datos['imagenes'])) {
                $this->guardarImagenes($articulo, $datos['imagenes']);
            }

            // 4. Reordenar imágenes si es necesario
            if (!empty($datos['imagenes_orden'])) {
                $this->reordenarImagenes($articulo, $datos['imagenes_orden']);
            }

            return $articulo->load(['categoria', 'marca', 'imagenes', 'variantes']);
        });
    }

    /**
     * Guardar imágenes del artículo
     */
    protected function guardarImagenes(Articulo $articulo, array $imagenes): void
    {
        $orden = $articulo->imagenes->max('orden') ?? 0;

        foreach ($imagenes as $imagen) {
            if ($imagen) {
                $uuid = Str::uuid() . '.' . $imagen->extension();
                $ruta = "articulos/{$articulo->id}/{$uuid}";

                // Guardar archivo
                Storage::disk('public')->put($ruta, file_get_contents($imagen));

                // Obtener metadatos
                $metadatos = $this->obtenerMetadatosImagen($imagen);

                // Crear registro
                $articulo->imagenes()->create([
                    'ruta' => $ruta,
                    'orden' => ++$orden,
                    'metadatos' => $metadatos,
                ]);

                // Despachar job para procesamiento (thumbnails, optimización)
                // TODO: dispatch(new ProcessArticuloImage($articuloImagen));
            }
        }
    }

    /**
     * Eliminar imágenes
     */
    protected function eliminarImagenes(array $imagenIds): void
    {
        $imagenes = ArticuloImagen::whereIn('id', $imagenIds)->get();

        foreach ($imagenes as $imagen) {
            // Eliminar archivo
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }

            // Eliminar registro
            $imagen->delete();
        }
    }

    /**
     * Reordenar imágenes
     */
    protected function reordenarImagenes(Articulo $articulo, array $orden): void
    {
        foreach ($orden as $index => $imagenId) {
            $articulo->imagenes()->where('id', $imagenId)->update(['orden' => $index + 1]);
        }
    }

    /**
     * Obtener metadatos de imagen usando funciones básicas de PHP
     */
    protected function obtenerMetadatosImagen($archivo): array
    {
        try {
            // Obtener información básica del archivo
            $size = $archivo->getSize();
            $mime = $archivo->getMimeType();

            // Intentar obtener dimensiones usando getimagesize si es posible
            $imagePath = $archivo->getRealPath();
            $imageInfo = @getimagesize($imagePath);

            $metadatos = [
                'size' => $size,
                'mime' => $mime,
            ];

            // Agregar dimensiones si están disponibles
            if ($imageInfo !== false) {
                $metadatos['width'] = $imageInfo[0];
                $metadatos['height'] = $imageInfo[1];
            }

            return $metadatos;
        } catch (\Exception $e) {
            // En caso de error, devolver información básica
            return [
                'size' => $archivo->getSize(),
                'mime' => $archivo->getMimeType(),
            ];
        }
    }

    /**
     * Eliminar artículo (soft delete)
     */
    public function eliminarArticulo(Articulo $articulo): bool
    {
        return $articulo->delete();
    }

    /**
     * Restaurar artículo eliminado
     */
    public function restaurarArticulo(Articulo $articulo): bool
    {
        return $articulo->restore();
    }

    /**
     * Activar/desactivar artículo
     */
    public function toggleActivo(Articulo $articulo): Articulo
    {
        $articulo->update(['activo' => !$articulo->activo]);

        return $articulo;
    }

    /**
     * Destacar/des-destacar artículo
     */
    public function toggleDestacado(Articulo $articulo): Articulo
    {
        $articulo->update(['destacado' => !$articulo->destacado]);

        return $articulo;
    }

    public function getOfertasHome(int $limit = 6)
    {
        return Articulo::ofertas()
            ->active()
            ->with(['imagenes', 'marca'])
            ->orderBy('porcentaje_descuento', 'desc') // Mostrar primero los descuentos más grandes
            ->take($limit)
            ->get();
    }

    /**
     * Obtiene el detalle del artículo con sus asociaciones
     */
    public function getDetalleArticulo(string $slug)
    {
        // Cargamos todas las relaciones posibles de las variantes
        $articulo = Articulo::with([
            'variantes.color',
            'variantes.talla',
            //'variantes.sabor', // Asumiendo que añadiste la tabla/columna sabores
            'categoria',
            'marca'
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Agrupamos las opciones disponibles de forma única para los selects
        $articulo->opciones = [
            'colores' => $articulo->variantes->pluck('color')->unique()->filter(),
            'tallas'  => $articulo->variantes->pluck('talla')->unique()->filter(),
            //'sabores' => $articulo->variantes->pluck('sabor')->unique()->filter(), // Nueva variante
        ];

        $articulo->puntos_ganados = floor($articulo->precio_venta);
        // Creamos un mapa de stock para JS: "color_id-talla_id" => stock
        $mapaStock = [];
        foreach ($articulo->variantes as $v) {
            $key = ($v->color_id ?? 0) . '-' . ($v->talla_id ?? 0);
            $mapaStock[$key] = $v->stock;
        }
        $articulo->mapa_stock = $mapaStock;

        return $articulo;
    }


    public function getImagenPrincipalAttribute()
    {
        return $this->articulo->getImagenPrincipalAttribute();
    }
}

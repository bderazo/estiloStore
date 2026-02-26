<?php
// app/Services/EntregaService.php
namespace App\Services;

use App\Models\DireccionEntrega;
use App\Models\Sector;
use App\Models\Pedido;
use Illuminate\Support\Collection;

class EntregaService
{
    // Ubicaciones que requieren dirección detallada
    const UBICACIONES_DETALLADAS = [
        'Quevedo',
        'Buena Fe',
        'Valencia',
        'La Maná',
        'El Empalme',
        'Pichincha',
        'Mocache',
        'Quinsaloma'
    ];

    /**
     * Verifica si una ubicación requiere dirección detallada
     */
    public function requiereDireccionDetallada(string $ubicacion): bool
    {
        return in_array($ubicacion, self::UBICACIONES_DETALLADAS);
    }

    /**
     * Obtiene los sectores disponibles para una ubicación
     */
    public function getSectoresPorUbicacion(string $ubicacion): Collection
    {
        return Sector::byUbicacion($ubicacion)
            ->activos()
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Crea o actualiza una dirección detallada
     */
    public function guardarDireccionDetallada(array $data): DireccionEntrega
    {
        // Si viene pedido_id, asociamos la dirección al pedido
        if (isset($data['pedido_id'])) {
            $data['activo'] = true;
        }

        return DireccionEntrega::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'pedido_id' => $data['pedido_id'] ?? null
            ],
            $data
        );
    }

    /**
     * Obtiene las estadísticas de entregas por sector
     */
    public function getEstadisticasPorSector(string $ubicacion = null): Collection
    {
        $query = Sector::withCount([
            'entregas as total_entregas',
            'entregas as entregas_completadas' => function ($q) {
                $q->where('estado_entrega', 'entregado');
            },
            'entregas as entregas_pendientes' => function ($q) {
                $q->whereIn('estado_entrega', ['pendiente', 'asignado', 'en_ruta']);
            }
        ]);

        if ($ubicacion) {
            $query->where('ubicacion_principal', $ubicacion);
        }

        return $query->get();
    }

    /**
     * Genera reporte de entregas por sectores
     */
    public function generarReporteSectores(array $filtros = [])
    {
        $query = DireccionEntrega::with(['user', 'sector', 'pedido'])
            ->whereHas('pedido', function ($q) use ($filtros) {
                if (isset($filtros['fecha_inicio'])) {
                    $q->whereDate('created_at', '>=', $filtros['fecha_inicio']);
                }
                if (isset($filtros['fecha_fin'])) {
                    $q->whereDate('created_at', '<=', $filtros['fecha_fin']);
                }
            });

        if (isset($filtros['ubicacion'])) {
            $query->where('ubicacion', $filtros['ubicacion']);
        }

        if (isset($filtros['sector_id'])) {
            $query->where('sector_id', $filtros['sector_id']);
        }

        return $query->get();
    }
}
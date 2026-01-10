<?php

namespace App\Services;

use App\Models\ArticuloVariante;
use Illuminate\Support\Facades\DB;

class ArticuloVarianteService
{
    /**
     * Reservar stock para una pre-venta
     * Usa locking pessimista para evitar race conditions
     */
    public function reservar(ArticuloVariante $variante, int $cantidad): bool
    {
        return DB::transaction(function () use ($variante, $cantidad) {
            // Bloquear fila para actualización
            $varianteActual = ArticuloVariante::lockForUpdate()->find($variante->id);

            // Validar disponibilidad
            $disponible = $varianteActual->stock - $varianteActual->reserved;
            if ($disponible < $cantidad) {
                throw new \Exception(
                    "No hay suficiente stock. Disponible: {$disponible}, Solicitado: {$cantidad}"
                );
            }

            // Reservar
            $varianteActual->increment('reserved', $cantidad);

            return true;
        });
    }

    /**
     * Liberar reserva (cancelar pre-venta)
     */
    public function liberar(ArticuloVariante $variante, int $cantidad): bool
    {
        return DB::transaction(function () use ($variante, $cantidad) {
            $varianteActual = ArticuloVariante::lockForUpdate()->find($variante->id);

            // Validar que hay suficiente reservado
            if ($varianteActual->reserved < $cantidad) {
                throw new \Exception(
                    "No se puede liberar {$cantidad}. Reservado: {$varianteActual->reserved}"
                );
            }

            $varianteActual->decrement('reserved', $cantidad);

            return true;
        });
    }

    /**
     * Decrementar stock confirmando venta
     * Reduce tanto stock como reserved
     */
    public function decrementar(ArticuloVariante $variante, int $cantidad): bool
    {
        return DB::transaction(function () use ($variante, $cantidad) {
            $varianteActual = ArticuloVariante::lockForUpdate()->find($variante->id);

            // Validar stock suficiente
            if ($varianteActual->stock < $cantidad) {
                throw new \Exception(
                    "No hay suficiente stock para decrementar. Stock: {$varianteActual->stock}, Solicitado: {$cantidad}"
                );
            }

            // Decrementar stock
            $varianteActual->decrement('stock', $cantidad);

            // Si hay cantidad reservada, decrementarla también
            if ($varianteActual->reserved > 0) {
                $decrementarReserved = min($varianteActual->reserved, $cantidad);
                $varianteActual->decrement('reserved', $decrementarReserved);
            }

            return true;
        });
    }

    /**
     * Incrementar stock (devolución o ajuste)
     */
    public function incrementar(ArticuloVariante $variante, int $cantidad): bool
    {
        return DB::transaction(function () use ($variante, $cantidad) {
            $varianteActual = ArticuloVariante::lockForUpdate()->find($variante->id);

            $varianteActual->increment('stock', $cantidad);

            return true;
        });
    }

    /**
     * Obtener stock disponible (stock - reserved)
     */
    public function obtenerDisponible(ArticuloVariante $variante): int
    {
        return $variante->stock - $variante->reserved;
    }

    /**
     * Validar si hay stock suficiente
     */
    public function tieneSuficienteStock(ArticuloVariante $variante, int $cantidad): bool
    {
        $disponible = $this->obtenerDisponible($variante);

        return $disponible >= $cantidad;
    }

    /**
     * Obtener información completa de stock
     */
    public function obtenerInfoStock(ArticuloVariante $variante): array
    {
        $disponible = $this->obtenerDisponible($variante);

        return [
            'stock_total' => $variante->stock,
            'reserved' => $variante->reserved,
            'disponible' => $disponible,
            'sku' => $variante->sku,
            'precio' => $variante->precio_final,
            'activo' => $variante->activo,
        ];
    }

    /**
     * Ajustar stock manualmente (uso administrativo)
     * Requiere justificación
     */
    public function ajustarStock(
        ArticuloVariante $variante,
        int $cantidad,
        string $razon = 'Ajuste manual',
        ?string $notas = null
    ): bool {
        return DB::transaction(function () use ($variante, $cantidad, $razon, $notas) {
            $varianteActual = ArticuloVariante::lockForUpdate()->find($variante->id);

            if ($cantidad > 0) {
                $varianteActual->increment('stock', $cantidad);
            } elseif ($cantidad < 0) {
                $decrementarPor = abs($cantidad);
                if ($varianteActual->stock < $decrementarPor) {
                    throw new \Exception(
                        "No se puede decrementar. Stock: {$varianteActual->stock}, Solicitado: {$decrementarPor}"
                    );
                }
                $varianteActual->decrement('stock', $decrementarPor);
            }

            // TODO: Registrar en tabla de auditoría de ajustes de stock
            // AjusteStock::create([
            //     'articulo_variante_id' => $varianteActual->id,
            //     'cantidad' => $cantidad,
            //     'razon' => $razon,
            //     'notas' => $notas,
            //     'usuario_id' => auth()->id(),
            // ]);

            return true;
        });
    }

    /**
     * Transferir stock entre variantes del mismo artículo
     */
    public function transferir(
        ArticuloVariante $origen,
        ArticuloVariante $destino,
        int $cantidad
    ): bool {
        return DB::transaction(function () use ($origen, $destino, $cantidad) {
            // Validar mismo artículo
            if ($origen->articulo_id !== $destino->articulo_id) {
                throw new \Exception('Las variantes deben pertenecer al mismo artículo');
            }

            // Bloquear ambas filas
            $origenActual = ArticuloVariante::lockForUpdate()->find($origen->id);
            $destinoActual = ArticuloVariante::lockForUpdate()->find($destino->id);

            // Validar disponibilidad en origen
            if ($origenActual->stock < $cantidad) {
                throw new \Exception(
                    "Stock insuficiente en origen. Disponible: {$origenActual->stock}"
                );
            }

            // Transferir
            $origenActual->decrement('stock', $cantidad);
            $destinoActual->increment('stock', $cantidad);

            return true;
        });
    }
}

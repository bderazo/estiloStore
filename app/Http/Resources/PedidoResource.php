<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'codigo_reserva' => $this->codigo_reserva,
            'total' => (float) $this->total,
            'costo_envio' => (float) $this->costo_envio,
            'estado' => $this->estado,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'phone' => $this->user->phone,
                    'created_at' => optional($this->user->created_at)->toISOString()
                ];
            }),
            
            'transporte' => $this->whenLoaded('transporte', function () {
                return [
                    'id' => $this->transporte->id,
                    'cooperativa' => $this->transporte->cooperativa,
                    'ruta' => $this->transporte->ruta,
                    'precio' => (float) $this->transporte->precio,
                    'tiempo_estimado' => $this->transporte->tiempo_estimado
                ];
            }),
            
            'pagos' => $this->whenLoaded('pagos', function () {
                return $this->pagos->map(function ($pago) {
                    return [
                        'id' => $pago->id,
                        'monto' => (float) $pago->monto,
                        'comprobante_ruta' => $pago->comprobante_ruta,
                        'estado' => $pago->estado,
                        'observacion' => $pago->observacion,
                        'created_at' => $pago->created_at->toISOString()
                    ];
                });
            }),
            
            'detalles' => $this->whenLoaded('detalles', function () {
                return $this->detalles->map(function ($detalle) {
                    return [
                        'id' => $detalle->id,
                        'cantidad' => $detalle->cantidad,
                        'precio_unitario' => (float) $detalle->precio_unitario,
                        'variante' => $detalle->variante ? [
                            'id' => $detalle->variante->id,
                            'articulo' => $detalle->variante->articulo ? [
                                'id' => $detalle->variante->articulo->id,
                                'nombre' => $detalle->variante->articulo->nombre
                            ] : null,
                            'color' => $detalle->variante->color ? [
                                'id' => $detalle->variante->color->id,
                                'nombre' => $detalle->variante->color->nombre
                            ] : null,
                            'talla' => $detalle->variante->talla ? [
                                'id' => $detalle->variante->talla->id,
                                'nombre' => $detalle->variante->talla->nombre
                            ] : null
                        ] : null
                    ];
                });
            })
        ];
    }
}
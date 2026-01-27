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
            'total' => $this->total,
            'costo_envio' => $this->costo_envio,
            'estado' => $this->estado,
            'created_at' => $this->created_at,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'created_at' => $this->user->created_at
            ] : null,
            'transporte' => $this->transporte ? [
                'id' => $this->transporte->id,
                'cooperativa' => $this->transporte->cooperativa,
                'ruta' => $this->transporte->ruta,
                'precio' => $this->transporte->precio,
                'tiempo_estimado' => $this->transporte->tiempo_estimado
            ] : null,
            'pagos' => PedidoPagoResource::collection($this->whenLoaded('pagos')),
            'detalles' => $this->whenLoaded('detalles', function () {
                return $this->detalles->map(function ($detalle) {
                    return [
                        'id' => $detalle->id,
                        'cantidad' => $detalle->cantidad,
                        'precio_unitario' => $detalle->precio_unitario,
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
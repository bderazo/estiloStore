<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MetodoPagoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'links' => [
                    'next' => $this->nextPageUrl(),
                    'previous' => $this->previousPageUrl()
                ]
            ]
        ];
    }
    
    public function with($request)
    {
        return [
            'meta' => [
                'tipos_pago' => [
                    'Transferencia' => 'Transferencia Bancaria',
                    'QR' => 'Pago QR',
                    'Efectivo' => 'Efectivo',
                    'Otro' => 'Otro Método'
                ],
                'total_activos' => $this->collection->where('activo', true)->count(),
                'total_inactivos' => $this->collection->where('activo', false)->count()
            ]
        ];
    }
}
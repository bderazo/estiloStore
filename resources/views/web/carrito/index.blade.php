@extends('web.layouts.base')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: var(--primary-color);">Mi Carrito de Compras</h2>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: var(--secondary-color-pastel);">
        <div class="card-body p-4">
            <label class="fw-bold mb-2">¿Tienes el código del catálogo? Cárgalo aquí:</label>
            <div class="input-group">
                <input type="text" id="sku_search" class="form-control border-0" placeholder="Escribe el código ej: ART-001...">
                <button class="btn btn-dark px-4" onclick="buscarCodigo()">Buscar</button>
            </div>
            <div id="quick_results" class="mt-3"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small uppercase">
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cant.</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/'.$item['imagen']) }}" width="60" class="rounded me-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $item['nombre'] }}</h6>
                                        <small class="text-muted">{{ $item['color'] }} / {{ $item['talla'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item['precio'], 2) }}</td>
                            <td>{{ $item['cantidad'] }}</td>
                            <td class="fw-bold">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                            <td><button class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-md-3">
                    <a href="#" class="card h-100 border-0 shadow-sm text-center p-3 text-decoration-none">
                        <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                        <span class="small fw-bold text-dark">Descargar Catálogo</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <button class="card h-100 border-0 shadow-sm text-center p-3 w-100" data-bs-toggle="modal" data-bs-target="#cotizacionModal">
                        <i class="fas fa-calculator fa-2x text-primary mb-2"></i>
                        <span class="small fw-bold text-dark">Ver Cotización</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="card h-100 border-0 shadow-sm text-center p-3 w-100 {{ !$totales['puede_pagar'] ? 'opacity-50' : '' }}" {{ !$totales['puede_pagar'] ? 'disabled' : '' }}>
                        <i class="fas fa-credit-card fa-2x text-success mb-2"></i>
                        <span class="small fw-bold text-dark">Pagar Ahora</span>
                    </button>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-3 bg-light">
                        <i class="fas fa-layer-group fa-2x text-secondary mb-2"></i>
                        <span class="small fw-bold text-muted">Acumular Pedido (Próximamente)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <h5 class="fw-bold mb-4">Resumen de Compra</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span class="fw-bold">${{ number_format($totales['subtotal'], 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Puntos Ser Dama</span>
                    <span class="badge bg-info text-dark">+{{ $totales['puntos'] }} pts</span>
                </div>
                <hr>
                @if(!$totales['puede_pagar'])
                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle me-2"></i> 
                        Faltan <strong>{{ 4 - $totales['cantidad_items'] }}</strong> productos para poder pagar (Mínimo 4).
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cotizacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Pre-Visualización de Cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tu pedido actual suma <strong>${{ number_format($totales['subtotal'], 2) }}</strong>.</p>
                <p>Recuerda que los precios pueden variar al momento de confirmar el pago según el stock disponible.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark w-100 rounded-pill">Imprimir PDF</button>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('web.layouts.base')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: var(--primary-color);">Mi Carrito de Compras</h2>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: var(--secondary-color-pastel);">
        <div class="card-body p-4">
            <label class="fw-bold mb-2 fs-3">¿Tienes el código del catálogo? Cárgalo aquí:</label>
            <div class="input-group">
                <input type="text" id="sku_search" class="form-control border-0 fs-2" placeholder="Ej: ART-001..." autocomplete="off">
                <button class="btn btn-dark px-4 fs-2" type="button" onclick="buscarCodigo()">Buscar</button>
            </div>
            <div id="quick_results" class="mt-3"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-muted small">
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cant.</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $id => $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item['imagen'] ? asset('storage/'.$item['imagen']) : asset('assets/img/no-product.png') }}" width="60" class="rounded me-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $item['nombre'] }}</h6>
                                        <small class="text-muted">{{ $item['color'] }} / {{ $item['talla'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item['precio'], 2) }}</td>
                            <td class="fw-bold">{{ $item['cantidad'] }}</td>
                            <td class="fw-bold">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                            <td>
                                <form action="{{ route('web.carrito.eliminar', $id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Tu carrito está vacío.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-md-3">
                    <a href="{{ isset($folletoPrincipal) ? route('folleto.descargar', $folletoPrincipal->id) : '#' }}" class="card h-100 border-0 shadow-sm text-center p-3 text-decoration-none">
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
                    <form action="{{ route('web.carrito.pagar') }}" method="POST" class="h-100">
                        @csrf
                        <button type="submit" class="card h-100 border-0 shadow-sm text-center p-3 w-100 {{ !$totales['puede_pagar'] ? 'opacity-50' : '' }}" {{ !$totales['puede_pagar'] ? 'disabled' : '' }}>
                            <i class="fas fa-credit-card fa-2x text-success mb-2"></i>
                            <span class="small fw-bold text-dark">Pagar Ahora</span>
                        </button>
                    </form>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-3 bg-light">
                        <i class="fas fa-layer-group fa-2x text-secondary mb-2"></i>
                        <span class="small fw-bold text-muted">Acumular Pedido</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <h5 class="fw-bold mb-4">Resumen de Compra</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span class="fw-bold">${{ number_format($totales['subtotal'] ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Puntos Ser Dama</span>
                    <span class="badge bg-info text-dark">+{{ $totales['puntos'] ?? 0 }} pts</span>
                </div>
                <hr>
                @if(!($totales['puede_pagar'] ?? false))
                    <div class="alert alert-warning small">
                        Faltan <strong>{{ 4 - ($totales['cantidad_items'] ?? 0) }}</strong> productos para pagar.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cotizacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header border-0"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <i class="fas fa-file-invoice-dollar fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Pre-Visualización</h5>
                <p>Total aproximado: <strong>${{ number_format($totales['subtotal'] ?? 0, 2) }}</strong></p>
                <button class="btn btn-dark w-100 rounded-pill mt-3">Imprimir PDF</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function buscarCodigo() {
    const sku = document.getElementById('sku_search').value;
    const resultsDiv = document.getElementById('quick_results');
    if(!sku) return;

    resultsDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i></div>';

    fetch(`{{ route('web.carrito.buscar') }}?codigo=${sku}`)
        .then(res => res.json())
        .then(data => {
            if(data.error) {
                resultsDiv.innerHTML = `<div class="alert alert-danger small">${data.error}</div>`;
                return;
            }

            let variantsHtml = '';
            data.variantes.forEach(v => {
                variantsHtml += `
                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-2">
                    <div class="col-3"><strong>${v.color} - ${v.talla}</strong></div>
                    <div class="col-1"><span class="text-primary fw-bold">$${parseFloat(v.precio).toFixed(2)}</span></div>
                    
                    <div class="col-4 d-flex align-items-center">
                        <input type="number" id="qty_${v.id}" class="form-control form-control-sm me-2" value="1" min="1" max="${v.stock_visual}" style="width:60px">
                        <small class="text-muted">Stock: ${v.stock_visual}</small>
                    </div>
                    <div class="col-4 text-end">
                        <button class="btn btn-sm btn-success" onclick="agregarArticulo(${v.id}, this)">Añadir</button>
                    </div>
                </div>`;
            });

            resultsDiv.innerHTML = `
                <div class="card border-0 shadow-sm p-3">
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <img src="${data.imagen ? '/storage/' + data.imagen : '/assets/img/no-product.png'}" width="70" class="rounded">
                        </div>
                        <div class="col">
                            <h6 class="fw-bold mb-0">${data.nombre}</h6>
                            <!--<span class="text-primary fw-bold">$${parseFloat(data.precio).toFixed(2)}</span>-->
                        </div>
                    </div>
                    ${variantsHtml}
                </div>`;
        });
}

function agregarArticulo(id, btn) {
    const cant = document.getElementById(`qty_${id}`).value;
    btn.disabled = true;

    fetch('{{ route("web.carrito.add") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ variante_id: id, cantidad: cant })
    })
    .then(res => res.json())
    .then(data => {
        if(data.error) { alert(data.error); btn.disabled = false; }
        else { window.location.href = "{{ route('web.carrito.index') }}"; } // Recarga forzada limpia
    });
}
</script>
@endpush
@endsection
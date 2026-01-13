@extends('web.layouts.base')

@section('content')
<style>
    /* Estilos para mejorar la experiencia de usuario en el botón bloqueado */
    .cursor-not-allowed {
        cursor: not-allowed !important;
        pointer-events: all !important; /* Permite detectar el clic para lanzar el alert */
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .btn-hover-success:not([disabled]):hover {
        transform: translateY(-5px);
        background-color: #f8fff9 !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .progress {
        background-color: #eee;
        overflow: visible;
    }
</style>

<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: var(--primary-color);">Mi Carrito de Compras</h2>

    {{-- Buscador de SKU --}}
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
        {{-- Tabla de Productos --}}
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
                                        <img src="{{ $item['imagen'] ? asset($item['imagen']) : asset('assets/img/no-product.png') }}" width="60" class="rounded me-3">
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
                                    <button class="btn btn-sm btn-danger" onclick="eliminarArticulo({{ $item['id'] ?? $id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Tu carrito está vacío.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Botones de Acción Inferiores --}}
            <div class="row g-3 mt-4">
                <div class="col-md-3">
                    <a href="{{ isset($folletoPrincipal) ? route('folleto.descargar', $folletoPrincipal->id) : '#' }}" class="card h-100 border-0 shadow-sm text-center p-3 text-decoration-none">
                        <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                        <span class="small fw-bold text-dark">Descargar Catálogo</span>
                    </a>
                </div>

                <div class="col-md-3">
                    {{-- Botón Pagar Ahora con feedback visual --}}
                    <button type="button" onclick="pagarReserva()" id="btnPagarAccion"
                        class="card h-100 border-0 shadow-sm text-center p-3 w-100 transition-all 
                        {{ !$totales['puede_pagar'] ? 'opacity-50 cursor-not-allowed' : 'btn-hover-success' }}">
                        <i class="fas fa-credit-card fa-2x {{ !$totales['puede_pagar'] ? 'text-secondary' : 'text-success' }} mb-2"></i>
                        <span class="small fw-bold text-dark">Pagar Ahora</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Resumen de Compra Lateral --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <h5 class="fw-bold mb-4">Resumen de Compra</h5>

                {{-- Barra de Progreso UX --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold">Progreso del pedido</span>
                        <span class="small fw-bold text-primary">{{ $totales['cantidad_items'] ?? 0 }}/4 Artículos</span>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 10px;">
                        @php
                            $cantidad = $totales['cantidad_items'] ?? 0;
                            $porcentaje = ($cantidad / 4) * 100;
                            $porcentaje = $porcentaje > 100 ? 100 : $porcentaje;
                            $colorBarra = $porcentaje < 100 ? 'bg-warning' : 'bg-success';
                        @endphp
                        <div class="progress-bar progress-bar-striped progress-bar-animated {{ $colorBarra }}" 
                             role="progressbar" style="width: {{ $porcentaje }}%"></div>
                    </div>
                    
                    @if (!($totales['puede_pagar'] ?? false))
                        <div class="mt-3 p-2 bg-light rounded-3 text-center">
                            <small class="text-dark">
                                <i class="fas fa-shopping-bag text-warning me-1"></i> 
                                Faltan <strong>{{ 4 - $cantidad }}</strong> productos para poder realizar tu reserva.
                            </small>
                        </div>
                    @else
                        <div class="mt-3 p-2 bg-light rounded-3 text-center border border-success">
                            <small class="text-success fw-bold">
                                <i class="fas fa-check-circle me-1"></i> ¡Mínimo alcanzado! Ya puedes pagar.
                            </small>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span class="fw-bold">${{ number_format($totales['subtotal'] ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Puntos Ser Dama</span>
                    <span class="badge bg-info text-dark">+{{ $totales['puntos'] ?? 0 }} pts</span>
                </div>
                <hr>
                <div class="text-muted small">
                    * Recuerda que el pedido mínimo es de 4 artículos.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function buscarCodigo() {
        const sku = document.getElementById('sku_search').value;
        const resultsDiv = document.getElementById('quick_results');
        if (!sku) return;

        resultsDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i></div>';

        fetch(`{{ route('web.carrito.buscar') }}?codigo=${sku}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
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
                                <img src="${data.imagen ? data.imagen : '/assets/img/no-product.png'}" width="70" class="rounded">
                            </div>
                            <div class="col">
                                <h6 class="fw-bold mb-0">${data.nombre}</h6>
                            </div>
                        </div>
                        ${variantsHtml}
                    </div>`;
            });
    }

    function agregarArticulo(id, btn) {
        const cant = document.getElementById(`qty_${id}`).value;
        btn.disabled = true;

        fetch('{{ route('web.carrito.add') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ variante_id: id, cantidad: cant })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                btn.disabled = false;
            } else {
                window.location.href = "{{ route('web.carrito.index') }}";
            }
        });
    }

    function eliminarArticulo(id) {
        if (!confirm('¿Quitar este artículo?')) return;
        fetch('{{ route('web.carrito.remove') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ variante_id: id })
        })
        .then(res => res.json())
        .then(data => { location.reload(); });
    }

    function pagarReserva() {
        // Validación extra por JavaScript para el usuario despistado
        const cantidadActual = {{ $totales['cantidad_items'] ?? 0 }};
        if (cantidadActual < 4) {
            alert("¡Atención! No has alcanzado el mínimo de 4 artículos. \n\nTe faltan " + (4 - cantidadActual) + " productos para poder procesar el pago.");
            return;
        }

        const btn = document.getElementById('btnPagarAccion');
        btn.disabled = true;

        fetch('{{ route('web.carrito.checkout') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert("Atención: " + (data.detalles ? data.detalles.join("\n") : data.error));
                btn.disabled = false;
            } else if (data.success) {
                alert("¡Reserva realizada con éxito!");
                window.location.href = "{{ url('/mis-reservas') }}/" + data.pedido_id;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
        });
    }
</script>
@endpush
@endsection
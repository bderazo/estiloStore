@extends('web.layouts.base')

@section('content')
    <div class="container py-5">
        {{-- Mensajes de Error y Éxito --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0 mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row">
            {{-- SECCIÓN IZQUIERDA: DETALLES --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">Reserva: {{ $pedido->codigo_reserva }}</h4>
                        <span class="badge bg-dark">{{ strtoupper($pedido->estado) }}</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="small text-muted">
                                    <th>Producto</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedido->detalles as $detalle)
                                    <tr>
                                        <td>
                                            <p class="mb-0 fw-bold">{{ $detalle->variante->articulo->nombre }}</p>
                                            <small class="text-muted">
                                                {{ $detalle->variante->color->nombre ?? 'N/A' }} | {{ $detalle->variante->talla->nombre ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            {{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ÚNICA SECCIÓN DE TOTALES --}}
                    <div class="bg-light p-3 rounded-4 mt-3 border">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal Productos:</span>
                            <span class="fw-bold">${{ number_format($pedido->total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Costo de Envío:</span>
                            <span class="fw-bold text-success" id="resumen_envio">
                                {{ $pedido->transporte_id ? '$'.number_format($pedido->costo_envio, 2) : 'Pendiente selección' }}
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5">TOTAL A CANCELAR:</span>
                            <span class="fw-bold text-primary fs-4" id="resumen_total">
                                ${{ number_format($pedido->total + $pedido->costo_envio, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Historial de Pagos --}}
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h5 class="fw-bold mb-3 small text-uppercase">Historial de Abonos</h5>
                    @if ($pedido->pagos->isEmpty())
                        <p class="small text-muted mb-0 italic">No hay pagos registrados.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($pedido->pagos as $pago)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                    <div>
                                        <small class="d-block text-muted">{{ $pago->created_at->format('d/m/Y H:i') }}</small>
                                        <strong class="fs-5">${{ number_format($pago->monto, 2) }}</strong>
                                        <a href="{{ asset('storage/' . $pago->comprobante_ruta) }}" target="_blank" class="ms-2 btn btn-sm btn-link p-0 text-decoration-none text-primary">
                                            <i class="fas fa-eye"></i> Ver comprobante
                                        </a>
                                    </div>
                                    <span class="badge rounded-pill @if ($pago->estado == 'aprobado') bg-success @elseif($pago->estado == 'rechazado') bg-danger @else bg-warning text-dark @endif">
                                        {{ ucfirst($pago->estado) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- SECCIÓN DERECHA: GESTIÓN DE ENVÍO Y PAGO --}}
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 20px; z-index: 5;">
                    <div class="card border-0 shadow-sm p-4 rounded-4 border-top border-primary border-5">
                        <h5 class="fw-bold mb-3"><i class="fas fa-wallet me-2"></i>Completar mi Pedido</h5>

                        {{-- Botón para ver medios de pago --}}
            <div class="mb-4">
                <button type="button" class="btn btn-outline-primary w-100 py-3" onclick="toggleMediosPago()" id="btnTogglePagos">
                    <i class="fas fa-credit-card me-2"></i>Ver Medios de Pago Disponibles
                    <i class="fas fa-chevron-down ms-2" id="iconToggle"></i>
                </button>
            </div>
            
            {{-- Sección de Métodos de Pago (oculta inicialmente) --}}
            <div id="seccionMetodosPago" class="d-none">
                <div class="border rounded-4 p-3 mb-4 bg-light">
                    <h6 class="fw-bold mb-3 text-dark">
                        <i class="fas fa-university me-2"></i>Información para Transferencias/Depósitos
                    </h6>
                    
                    <div class="row g-3">
                        @foreach($metodosPago as $metodo)
                            <div class="col-12">
                                <div class="card border shadow-sm mb-3">
                                    <div class="card-body">
                                        {{-- Encabezado --}}
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="me-3">
                                                @if($metodo->logo_banco)
                                                    <img src="{{ asset('storage/' . $metodo->logo_banco) }}" 
                                                         alt="{{ $metodo->nombre_banco }}" 
                                                         style="width: 50px; height: 50px; object-fit: contain;">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-university"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $metodo->nombre_banco }}</h6>
                                                <span class="badge 
                                                    @if($metodo->tipo_pago == 'Transferencia') bg-primary
                                                    @elseif($metodo->tipo_pago == 'QR') bg-success
                                                    @endif">
                                                    {{ $metodo->tipo_pago }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        {{-- Información del método --}}
                                        @if($metodo->tipo_pago == 'Transferencia')
                                            <div class="row small">
                                                <div class="col-md-6 mb-2">
                                                    <span class="text-muted">Titular:</span>
                                                    <p class="fw-bold mb-1">{{ $metodo->nombre_titular }}</p>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <span class="text-muted">N° Cuenta:</span>
                                                    <p class="fw-bold mb-1 text-primary">{{ $metodo->numero_cuenta }}</p>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <span class="text-muted">Tipo de Cuenta:</span>
                                                    <p class="fw-bold mb-1">{{ $metodo->tipo_cuenta }}</p>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <span class="text-muted">Identificación:</span>
                                                    <p class="fw-bold mb-1">{{ $metodo->identificacion }}</p>
                                                </div>
                                            </div>
                                        @elseif($metodo->tipo_pago == 'QR')
                                            <div class="text-center">
                                                @if($metodo->imagen_qr)
                                                    <img src="{{ asset('storage/' . $metodo->imagen_qr) }}" 
                                                         alt="QR {{ $metodo->nombre_banco }}" 
                                                         class="img-fluid mb-2" 
                                                         style="max-width: 200px;">
                                                @endif
                                                <p class="small text-success mb-0">
                                                    <i class="fas fa-qrcode me-1"></i>Escanea el código QR para pagar
                                                </p>
                                            </div>
                                        @endif
                                        
                                        {{-- Instrucciones --}}
                                        @if($metodo->instrucciones)
                                            <div class="alert alert-light border mt-3 small">
                                                <i class="fas fa-info-circle text-primary me-1"></i>
                                                {{ $metodo->instrucciones }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Recordatorio --}}
                    <div class="alert alert-info border-0 small mt-3">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <strong>Importante:</strong> Después de realizar el pago, sube el comprobante en el formulario de abajo.
                    </div>
                </div>
            </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">1. SELECCIONA TU RUTA DE ENVÍO</label>
                            <div class="position-relative">
                                <select id="select_transporte" class="form-select form-select-lg border-2" onchange="actualizarEnvio(this.value)">
                                        <option value="">-- Escoge una ruta --</option> @foreach ($rutas as $ruta)
                                        <option value="{{ $ruta->id }}" {{ $pedido->transporte_id == $ruta->id ? 'selected' : '' }}>
                                            {{ $ruta->ruta }} (${{ number_format($ruta->precio, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="loader_transporte" class="position-absolute top-50 end-0 translate-middle-y me-5 d-none">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </div>
                            </div>
                            <div id="info_envio" class="mt-2 small text-primary {{ !$pedido->transporte_id ? 'd-none' : '' }}">
                                <i class="fas fa-truck me-1"></i> Cooperativa: <strong id="txt_cooperativa">{{ $pedido->transporte->cooperativa ?? '' }}</strong>
                            </div>
                        </div>

                        <hr>

                        <div id="seccion_pago" class="{{ !$pedido->transporte_id ? 'opacity-50' : '' }}">
                            <label class="form-label small fw-bold text-muted">2. REGISTRA TU PAGO</label>
                            
                            @if(!$pedido->transporte_id)
                                <div id="msg_bloqueo" class="alert alert-secondary py-2 small border-0 text-center">
                                    <i class="fas fa-lock me-1"></i> Selecciona transporte para habilitar el pago
                                </div>
                            @endif

                            <form action="{{ route('web.pedidos.subir_pago', $pedido->id) }}" method="POST" enctype="multipart/form-data" id="form_abono">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small">Monto que depositaste</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">$</span>
                                        <input type="number" step="0.01" name="monto" class="form-control border-start-0" placeholder="0.00" required 
                                            {{ !$pedido->transporte_id ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small">Foto del Comprobante</label>
                                    <input type="file" name="comprobante" class="form-control" accept="image/*" required
                                        {{ !$pedido->transporte_id ? 'disabled' : '' }}>
                                </div>
                                <button type="submit" id="btn_subir_pago" class="btn btn-dark w-100 fw-bold py-3 shadow-sm" {{ !$pedido->transporte_id ? 'disabled' : '' }}>
                                    <i class="fas fa-cloud-upload-alt me-2"></i>SUBIR PAGO Y ENVIAR A VALIDACIÓN
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 small border-0 shadow-sm rounded-4">
                        <i class="fas fa-info-circle me-1 text-primary"></i> 
                        Recuerda que tu envío será despachado una vez el pago sea validado por administración.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
 function actualizarEnvio(transporteId) {
    const infoEnvio = document.getElementById('info_envio');
    const resumenEnvio = document.getElementById('resumen_envio');
    const seccionPago = document.getElementById('seccion_pago');
    const formElements = document.getElementById('form_abono').querySelectorAll('input, button');
    const msgBloqueo = document.getElementById('msg_bloqueo');

    // SI DEJA EN BLANCO: Limpiar y bloquear todo
    if (!transporteId || transporteId === "") {
        infoEnvio.classList.add('d-none'); // Oculta "Cooperativa: ..."
        resumenEnvio.innerText = 'Pendiente selección'; // Texto original en la tabla
        
        // Bloquear pagos nuevamente
        seccionPago.classList.add('opacity-50');
        formElements.forEach(el => el.disabled = true);
        if(msgBloqueo) msgBloqueo.classList.remove('d-none');
        
        // Opcional: Resetear el total a solo el valor del pedido
        document.getElementById('resumen_total').innerText = '$' + "{{ number_format($pedido->total, 2) }}";
        return; 
    }

    // SI HAY VALOR: Ejecutar AJAX
    const loader = document.getElementById('loader_transporte');
    const select = document.getElementById('select_transporte');
    loader.classList.remove('d-none');
    select.disabled = true;

    fetch("{{ route('web.pedidos.asignar_transporte', $pedido->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ transporte_id: transporteId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('resumen_envio').innerText = '$' + data.costo_envio;
            document.getElementById('resumen_total').innerText = '$' + data.total_final;
            document.getElementById('txt_cooperativa').innerText = data.cooperativa;
            infoEnvio.classList.remove('d-none');

            seccionPago.classList.remove('opacity-50');
            formElements.forEach(el => el.disabled = false);
            if(msgBloqueo) msgBloqueo.classList.add('d-none');
        }
    })
    .catch(err => alert("Error al procesar"))
    .finally(() => {
        loader.classList.add('d-none');
        select.disabled = false;
    });
}
function toggleMediosPago() {
    const seccion = document.getElementById('seccionMetodosPago');
    const icon = document.getElementById('iconToggle');
    const btn = document.getElementById('btnTogglePagos');
    
    if (seccion.classList.contains('d-none')) {
        // Mostrar
        seccion.classList.remove('d-none');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-primary', 'text-white');
    } else {
        // Ocultar
        seccion.classList.add('d-none');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
        btn.classList.remove('btn-primary', 'text-white');
        btn.classList.add('btn-outline-primary');
    }
}
    </script>
@endsection
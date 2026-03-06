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

            {{-- NUEVO: Formulario de dirección detallada para Quevedo --}}
            <div id="seccion_direccion_quevedo" class="mb-4 p-3 bg-light rounded-4 border {{ $pedido->transporte && str_contains($pedido->transporte->ruta, 'QUEVEDO') ? '' : 'd-none' }}">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-map-marker-alt text-primary me-2 fs-5"></i>
                    <h6 class="fw-bold mb-0">Dirección de entrega en Quevedo</h6>
                </div>
                
                @php
                    $direccion = $pedido->direccionEntrega;
                @endphp
                
                <form id="form_direccion_quevedo" onsubmit="event.preventDefault(); guardarDireccionQuevedo();">
                    @csrf
                    <input type="hidden" id="pedido_id" value="{{ $pedido->id }}">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Barrio <span class="text-danger">*</span></label>
                            <input type="text" 
                                class="form-control @error('barrio') is-invalid @enderror" 
                                id="barrio" 
                                name="barrio" 
                                value="{{ $direccion->barrio ?? old('barrio') }}"
                                placeholder="Ej: Las Orquídeas"
                                style="font-size: 1.2rem; padding: 0.6rem 0.75rem;"
                                required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Calle Principal <span class="text-danger">*</span></label>
                            <input type="text" 
                                class="form-control @error('calle_principal') is-invalid @enderror" 
                                id="calle_principal" 
                                name="calle_principal" 
                                value="{{ $direccion->calle_principal ?? old('calle_principal') }}"
                                placeholder="Ej: Av. Quito"
                                style="font-size: 1.2rem; padding: 0.6rem 0.75rem;"
                                required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Calle Secundaria <span class="text-danger">*</span></label>
                            <input type="text" 
                                class="form-control @error('calle_secundaria') is-invalid @enderror" 
                                id="calle_secundaria" 
                                name="calle_secundaria" 
                                value="{{ $direccion->calle_secundaria ?? old('calle_secundaria') }}"
                                placeholder="Ej: Calle 10 de Agosto"
                                style="font-size: 1.2rem; padding: 0.6rem 0.75rem;"
                                required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Color de la Casa</label>
                            <input type="text" 
                                class="form-control" 
                                id="color_casa" 
                                name="color_casa" 
                                value="{{ $direccion->color_casa ?? old('color_casa') }}"
                                placeholder="Ej: Blanca con rejas negras"
                                style="font-size: 1.2rem; padding: 0.6rem 0.75rem;">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Referencia</label>
                            <input type="text" 
                                class="form-control" 
                                id="referencia" 
                                name="referencia" 
                                value="{{ $direccion->referencia ?? old('referencia') }}"
                                placeholder="Ej: Frente al parque central"
                                style="font-size: 1.2rem; padding: 0.6rem 0.75rem;">
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 fw-bold mt-3" id="btn_guardar_direccion" style="font-size: 1.2rem; padding: 0.7rem;">
                                <i class="fas fa-save me-2"></i>Guardar Dirección
                            </button>
                        </div>
                        
                        <div id="mensaje_direccion" class="col-12 small d-none"></div>
                        
                        {{-- Indicador de estado --}}
                        @if($direccion)
                        <div class="col-12">
                            <div class="alert alert-success small mb-0">
                                <i class="fas fa-check-circle me-1"></i> Dirección guardada: 
                                <strong>{{ $direccion->barrio }}</strong>, 
                                {{ $direccion->calle_principal }} y {{ $direccion->calle_secundaria }}
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
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
                                {{-- Validación adicional para Quevedo -- MEJORADA --}}
                                @if($pedido->transporte && str_contains($pedido->transporte->ruta, 'QUEVEDO'))
                                    <div id="alerta_direccion_quevedo" class="alert alert-warning border-0 bg-warning bg-opacity-10 py-3 mb-3 rounded-4 {{ ($pedido->direccionEntrega) ? 'd-none' : '' }}">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-25 p-2 rounded-circle me-3">
                                                <i class="fas fa-exclamation-triangle text-warning fs-5"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block mb-1">Dirección pendiente</span>
                                                <span class="small">Para entregas en Quevedo, completa la dirección antes de subir el pago</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div id="alerta_direccion_quevedo" class="d-none"></div>
                                @endif
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
            const seccionDireccion = document.getElementById('seccion_direccion_quevedo');
            const select = document.getElementById('select_transporte');
            
            // Obtener el texto de la opción seleccionada para saber si es Quevedo
            const selectedOption = select.options[select.selectedIndex];
            const nombreRuta = selectedOption ? selectedOption.text : '';
            const esQuevedo = nombreRuta.includes('QUEVEDO'); // Cambiado a mayúsculas

            // SI DEJA EN BLANCO: Limpiar y bloquear todo
            if (!transporteId || transporteId === "") {
                infoEnvio.classList.add('d-none');
                resumenEnvio.innerText = 'Pendiente selección';
                
                // Bloquear pagos nuevamente
                seccionPago.classList.add('opacity-50');
                formElements.forEach(el => el.disabled = true);
                if(msgBloqueo) msgBloqueo.classList.remove('d-none');
                
                // Ocultar sección de dirección
                if (seccionDireccion) seccionDireccion.classList.add('d-none');
                
                // Resetear el total
                document.getElementById('resumen_total').innerText = '$' + "{{ number_format($pedido->total, 2) }}";
                return; 
            }

            // SI HAY VALOR: Ejecutar AJAX
            const loader = document.getElementById('loader_transporte');
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

                    // 🟢 NUEVO: Mostrar u ocultar formulario de dirección según la ruta
                    if (esQuevedo) {
                        console.log('Mostrando formulario para Quevedo');
                        if (seccionDireccion) seccionDireccion.classList.remove('d-none');
                    } else {
                        console.log('Ocultando formulario (no es Quevedo)');
                        if (seccionDireccion) seccionDireccion.classList.add('d-none');
                    }

                    seccionPago.classList.remove('opacity-50');
                    formElements.forEach(el => el.disabled = false);
                    if(msgBloqueo) msgBloqueo.classList.add('d-none');
                    
                    // 🟢 NUEVO: Verificar requisitos de pago
                    verificarRequisitosPago();
                }
            })
            .catch(err => {
                console.error("Error al procesar:", err);
                alert("Error al procesar la selección de transporte");
            })
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
                seccion.classList.remove('d-none');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary', 'text-white');
            } else {
                seccion.classList.add('d-none');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                btn.classList.remove('btn-primary', 'text-white');
                btn.classList.add('btn-outline-primary');
            }
        }

        // 🟢 NUEVA FUNCIÓN: Verificar requisitos antes de permitir el pago
        function verificarRequisitosPago() {
            const select = document.getElementById('select_transporte');
            const selectedOption = select.options[select.selectedIndex];
            const nombreRuta = selectedOption ? selectedOption.text : '';
            const esQuevedo = nombreRuta.includes('QUEVEDO');
            
            const btnPago = document.getElementById('btn_subir_pago');
            const alertaDireccion = document.getElementById('alerta_direccion_quevedo');
            
            console.log('Verificando requisitos - ¿Es Quevedo?', esQuevedo);
            
            if (esQuevedo) {
                // Verificar si la dirección está completa
                const barrio = document.getElementById('barrio').value.trim();
                const callePrincipal = document.getElementById('calle_principal').value.trim();
                const calleSecundaria = document.getElementById('calle_secundaria').value.trim();
                
                const direccionCompleta = barrio && callePrincipal && calleSecundaria;
                
                if (!direccionCompleta) {
                    btnPago.disabled = true;
                    if (alertaDireccion) {
                        alertaDireccion.classList.remove('d-none');
                        alertaDireccion.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Para entregas en Quevedo, debes completar la dirección de entrega antes de subir el pago.';
                    }
                } else {
                    btnPago.disabled = false;
                    if (alertaDireccion) alertaDireccion.classList.add('d-none');
                }
            } else {
                // Si no es Quevedo, habilitar si hay transporte seleccionado
                btnPago.disabled = !select.value;
                if (alertaDireccion) alertaDireccion.classList.add('d-none');
            }
        }

        function guardarDireccionQuevedo() {
            const btnGuardar = document.getElementById('btn_guardar_direccion');
            const mensajeDiv = document.getElementById('mensaje_direccion');
            
            // Validar campos requeridos
            const barrio = document.getElementById('barrio').value.trim();
            const callePrincipal = document.getElementById('calle_principal').value.trim();
            const calleSecundaria = document.getElementById('calle_secundaria').value.trim();
            
            if (!barrio || !callePrincipal || !calleSecundaria) {
                mostrarMensaje(mensajeDiv, 'Barrio, calle principal y calle secundaria son obligatorios', 'danger');
                return;
            }
            
            // Deshabilitar botón mientras se guarda
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';
            
            // Recopilar datos
            const data = {
                pedido_id: document.getElementById('pedido_id').value,
                barrio: barrio,
                calle_principal: callePrincipal,
                calle_secundaria: calleSecundaria,
                color_casa: document.getElementById('color_casa').value.trim(),
                referencia: document.getElementById('referencia').value.trim(),
                _token: '{{ csrf_token() }}'
            };
            
            // Enviar petición AJAX
            fetch('{{ route("web.pedidos.guardar_direccion") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje(mensajeDiv, '✓ Dirección guardada correctamente', 'success');
                    
                    // Actualizar la vista con la dirección guardada
                    const direccionHtml = `
                        <div class="alert alert-success small mb-0">
                            <i class="fas fa-check-circle me-1"></i> Dirección guardada: 
                            <strong>${barrio}</strong>, ${callePrincipal} y ${calleSecundaria}
                        </div>
                    `;
                    
                    // Buscar si ya existe un alert y reemplazarlo
                    const existingAlert = document.querySelector('#seccion_direccion_quevedo .alert-success');
                    if (existingAlert) {
                        existingAlert.outerHTML = direccionHtml;
                    } else {
                        document.querySelector('#seccion_direccion_quevedo .row.g-3').insertAdjacentHTML('beforeend', 
                            '<div class="col-12">' + direccionHtml + '</div>'
                        );
                    }
                    
                    // Verificar requisitos para pago
                    verificarRequisitosPago();
                    
                    // Ocultar mensaje después de 3 segundos
                    setTimeout(() => {
                        mensajeDiv.classList.add('d-none');
                    }, 3000);
                } else {
                    mostrarMensaje(mensajeDiv, data.message || 'Error al guardar la dirección', 'danger');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                mostrarMensaje(mensajeDiv, 'Error de conexión al guardar la dirección', 'danger');
            })
            .finally(() => {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Dirección';
            });
        }

        function mostrarMensaje(elemento, texto, tipo) {
            elemento.className = `col-12 small text-${tipo}`;
            elemento.innerText = texto;
            elemento.classList.remove('d-none');
        }

        // 🟢 Inicialización al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Página cargada, verificando requisitos...');
            
            // Verificar si ya hay una ruta seleccionada al cargar
            const select = document.getElementById('select_transporte');
            const seccionDireccion = document.getElementById('seccion_direccion_quevedo');
            
            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const nombreRuta = selectedOption ? selectedOption.text : '';
                const esQuevedo = nombreRuta.includes('QUEVEDO');
                
                if (esQuevedo && seccionDireccion) {
                    seccionDireccion.classList.remove('d-none');
                }
            }
            
            verificarRequisitosPago();
            
            // Eventos para validación en tiempo real
            const camposDireccion = ['barrio', 'calle_principal', 'calle_secundaria'];
            camposDireccion.forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.addEventListener('input', verificarRequisitosPago);
                }
            });
        });
    </script>
@endsection
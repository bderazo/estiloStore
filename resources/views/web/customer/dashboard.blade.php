<!doctype html>
<html lang="zxx">

<head>
    @include('web.index.partials.head-recursos')
</head>

<body>
    @include('web.layouts.preloader')
    @include('web.layouts.header')

<main class="main__content_wrapper">
    <section class="breadcrumb__section breadcrumb__bg" style="background: var(--primary-color); padding: 30px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white fw-bold mb-0">Panel de Emprendedora</h2>
                    <p class="text-white-50">¡Hola, {{ $user->nombres }}! Gestiona tus puntos y pedidos aquí.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }} p-2">
                        Estado: {{ $user->active ? 'Cuenta Activa' : 'Cuenta Bloqueada' }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="my__account--section section--padding" style="background: #f8f9fa;">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 15px;">
                                <div class="text-secondary mb-2"><i class="fas fa-star fa-2x"></i></div>
                                <h4 class="fw-bold mb-0">{{ $stats['puntos_actuales'] }}</h4>
                                <small class="text-muted">Mis Puntos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 15px;">
                                <div class="text-primary mb-2" style="color: var(--primary-color) !important;"><i class="fas fa-shopping-bag fa-2x"></i></div>
                                <h4 class="fw-bold mb-0">{{ $stats['total_compras'] }}</h4>
                                <small class="text-muted">Compras Exitosas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 15px;">
                                <div class="text-info mb-2"><i class="fas fa-clock fa-2x"></i></div>
                                <h4 class="fw-bold mb-0">{{ $stats['total_reservas'] }}</h4>
                                <small class="text-muted">Reservas Activas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm text-center p-3 {{ $user->fallos_reserva > 0 ? 'border-danger' : '' }}" style="border-radius: 15px;">
                                <div class="text-danger mb-2"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                                <h4 class="fw-bold mb-0">{{ $user->fallos_reserva }} / 2</h4>
                                <small class="text-muted">Fallos de Reserva</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->fallos_reserva == 1)
                <div class="col-12 mb-4">
                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" role="alert" style="border-radius: 12px;">
                        <i class="fas fa-info-circle me-3 fa-2x"></i>
                        <div>
                            <strong>¡Atención!</strong> Tienes un fallo acumulado. Recuerda que al segundo fallo por no completar un pago antes del domingo 12:00 PM, tu cuenta será suspendida automáticamente.
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0"><i class="fas fa-wallet text-secondary me-2"></i> Acciones Requeridas (Pagos Pendientes)</h5>
                        </div>
                        <div class="card-body">
                            @forelse($ordenesPendientes as $orden)
                            <div class="p-3 mb-3 border rounded d-md-flex align-items-center justify-content-between" style="background: #fffafa;">
                                <div>
                                    <span class="badge bg-dark mb-2">Orden #{{ $orden->id }}</span>
                                    <h6 class="mb-1">Vence: <span class="text-danger fw-bold">{{ $orden->fecha_limite->format('d/m/Y H:i') }}</span></h6>
                                    <p class="mb-0 small text-muted">Total: ${{ number_with_format($orden->total) }} | Artículos: {{ $orden->details->count() }}</p>
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <button class="btn btn-sm btn-secondary text-white px-4" style="border-radius: 8px;" 
                                            data-bs-toggle="modal" data-bs-target="#modalPago{{ $orden->id }}">
                                        Subir Voucher
                                    </button>
                                </div>
                            </div>

                            <div class="modal fade" id="modalPago{{ $orden->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="border-radius: 15px;">
                                        <form action="{{ route('customer.upload.payment', $orden->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold">Subir Comprobante</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small text-muted mb-4">Sube la foto del depósito o transferencia para la Orden #{{ $orden->id }}.</p>
                                                <div class="mb-3">
                                                    <label class="fw-bold small mb-1">Monto del abono/pago</label>
                                                    <input type="number" name="monto" step="0.01" class="form-control" placeholder="0.00" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="fw-bold small mb-1">Imagen del Voucher</label>
                                                    <input type="file" name="comprobante" class="form-control" accept="image/*" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="submit" class="btn btn-secondary w-100 text-white fw-bold">Enviar al Técnico</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                {{--<img src="{{ asset('assets/img/check-ok.png') }}" style="width: 60px; opacity: 0.5;">--}}
                                <p class="text-muted mt-2">No tienes pagos pendientes. ¡Estás al día!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                        <div class="card-body p-0">
                            <nav>
                                <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                                    <button class="nav-link active fw-bold py-3 px-4 border-0" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-orders" type="button" role="tab">Historial de Compras</button>
                                    <button class="nav-link fw-bold py-3 px-4 border-0" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-points" type="button" role="tab">Mis Puntos</button>
                                </div>
                            </nav>
                            <div class="tab-content p-4" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-orders" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table small">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Fecha</th>
                                                    <th>Total</th>
                                                    <th>Estado</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($historial as $h)
                                                <tr>
                                                    <td>#{{ $h->id }}</td>
                                                    <td>{{ $h->created_at->format('d/m/Y') }}</td>
                                                    <td>${{ number_with_format($h->total) }}</td>
                                                    <td>
                                                        <span class="badge {{ $h->estado == 'completado' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ strtoupper($h->estado) }}
                                                        </span>
                                                    </td>
                                                    <td><a href="#" class="text-secondary"><i class="fas fa-eye"></i></a></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $historial->links() }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-points" role="tabpanel">
                                    <ul class="list-group list-group-flush">
                                        @foreach($puntosLog as $log)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <h6 class="mb-0">{{ $log->motivo }}</h6>
                                                <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                            <span class="fw-bold text-success">+{{ $log->cantidad }} pts</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 text-center mb-4" style="border-radius: 20px; background: white;">
                        <!--
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ asset('assets/img/avatar-placeholder.png') }}" class="rounded-circle" style="width: 100px; border: 4px solid var(--secondary-color-pastel);">
                        </div>
                        -->
                        <h5 class="fw-bold mb-1">{{ $user->nombres }} {{ $user->apellidos }}</h5>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                        <hr>
                        <div class="text-start">
                            <p class="mb-2 small"><strong>Mi Código de Referida:</strong></p>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-sm fs-3" style="font-weight: bold;" value="{{ $user->codigo_referido }}" readonly id="refCode">
                                <button class="btn btn-outline-secondary btn-sm fs-4" type="button" onclick="copyRef()">Copiar</button>
                            </div>
                            <p class="text-muted" style="fs-6">Comparte este código con tus amigas y gana 50 puntos por cada una que se registre.</p>
                        </div>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-danger btn-sm w-100 mt-3 fs-5">Cerrar Sesión</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>

                    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; background: var(--primary-color);">
                        <h6 class="text-white fw-bold mb-3">¿Necesitas ayuda?</h6>
                        <p class="text-white-50 small">Si tienes problemas con la validación de tus pagos, contacta a soporte técnico vía WhatsApp.</p>
                        <a href="https://wa.me/593990273691" class="btn btn-light btn-sm w-100 fw-bold fs-5" style="color: var(--primary-color);">Soporte por WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function copyRef() {
        var copyText = document.getElementById("refCode");
        copyText.select();
        document.execCommand("copy");
        alert("¡Código copiado!: " + copyText.value);
    }
</script>

<style>
    .nav-tabs .nav-link.active {
        color: var(--secondary-color) !important;
        border-bottom: 3px solid var(--secondary-color) !important;
        background: none;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
    }
    .card-header {
        border-bottom: 1px solid #f0f0f0 !important;
    }
</style>

    @include('web.layouts.footer')
    @include('web.index.partials.modal-quickview')
    @include('web.index.partials.boton-scroll-top')
    @include('web.index.partials.scripts')
</body>

</html>
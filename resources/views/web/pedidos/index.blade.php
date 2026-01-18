@extends('web.layouts.base')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Mis Reservas</h2>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive p-3">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted">
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $p)
                    <tr>
                        <td class="fw-bold">{{ $p->codigo_reserva }}</td>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                        <td>${{ number_format($p->total, 2) }}</td>
                        <td>
                            <span class="fs-5 badge @if($p->estado == 'pendiente') bg-warning @elseif($p->estado == 'completado') bg-success @else bg-secondary @endif">
                                {{ ucfirst($p->estado) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('web.pedidos.show', $p->id) }}" class="btn btn-sm btn-dark fs-5">Ver Detalles / Pagar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h3 class="fw-bold mb-4 text-dark">Métodos de Pago Disponibles</h3>

            <form action="{{ route('web.pago.confirmar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    @foreach($metodos as $metodo)
                    <div class="col-md-6">
                        <label class="w-100 mb-0">
                            {{-- Radio button habilitado solo con sesión --}}
                            <input type="radio" name="metodo_pago_id" value="{{ $metodo->id }}" class="d-none" 
                                   {{ !$isLogged ? 'disabled' : '' }} required>

                            <div class="card h-100 shadow-sm payment-card p-3 {{ !$isLogged ? 'opacity-75' : '' }}">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm icon-box">
                                        @if($metodo->tipo_pago == 'Transferencia')
                                            <i class="fas fa-university text-primary"></i>
                                        @elseif($metodo->tipo_pago == 'QR')
                                            <i class="fas fa-qrcode text-success"></i>
                                        @else
                                            <i class="fas fa-wallet text-secondary"></i>
                                        @endif
                                    </div>

                                    <div class="ms-3">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $metodo->nombre_banco }}</h6>
                                        <small class="text-muted text-uppercase fw-bold" style="fs-5 letter-spacing: 0.5px;">
                                            {{ $metodo->tipo_pago }}
                                        </small>
                                    </div>
                                </div>

                                @if($isLogged)
                                    {{-- MOSTRAR DATOS SI HAY SESIÓN --}}
                                    @if($metodo->tipo_pago == 'QR')
                                        <div class="text-center p-2 bg-white rounded border border-dashed">
                                            @if($metodo->imagen_qr)
                                                <img src="{{ asset('storage/'.$metodo->imagen_qr) }}" class="img-fluid" style="max-height: 140px;" alt="QR">
                                                <p class="small mt-2 mb-0 fw-bold text-success">Escanea para pagar</p>
                                            @else
                                                <span class="text-muted small">QR no disponible</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="p-3 rounded" style="background: #fdfdfd; border: 1px solid #efefef;">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Titular:</span>
                                                <span class="small fw-bold">{{ $metodo->nombre_titular }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">N° Cuenta:</span>
                                                <span class="small fw-bold text-primary">{{ $metodo->numero_cuenta }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">CI/RUC:</span>
                                                <span class="small fw-bold">{{ $metodo->identificacion }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    {{-- BLOQUEO SI NO HAY SESIÓN --}}
                                    <div class="text-center py-4 bg-light rounded border">
                                        <i class="fas fa-lock text-muted mb-2"></i>
                                        <p class="mb-0" style="fs-6 color: #666;">
                                            Datos confidenciales.<br>
                                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--primary-color);">Inicia sesión</a> para verlos.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
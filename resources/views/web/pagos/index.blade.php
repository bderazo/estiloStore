@extends('web.layouts.base')

@section('title', 'Finalizar Pago')

@push('styles')
<style>
    /* Z-index bajo para que el sticky no tape el menú desplegable (Navbar) */
    .sticky-summary {
        position: -webkit-sticky;
        position: sticky;
        top: 100px; /* Distancia desde el tope de la ventana */
        z-index: 10; 
    }

    .payment-card {
        border: 2px solid #f0f0f0;
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }

    /* Efecto cuando el radio button está seleccionado */
    input[type="radio"]:checked + .payment-card {
        border-color: var(--primary-color) !important;
        background-color: #fff9fb;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Check visual para la tarjeta seleccionada */
    input[type="radio"]:checked + .payment-card::after {
        content: "\f058";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        top: 10px;
        right: 10px;
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .icon-box {
        width: 50px; 
        height: 50px; 
        background-color: #f8f9fa; 
        border: 1px solid #eee;
    }
</style>
@endpush

@section('content')
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

                @if($isLogged)
                <div class="card border-0 shadow-sm mt-4 p-4" style="border-radius: 15px;">
                    <h5 class="fw-bold mb-3"><i class="fas fa-file-invoice me-2 text-primary"></i>Adjuntar Comprobante</h5>
                    <div class="input-group">
                        <input type="file" name="comprobante" class="form-control" accept="image/*" required>
                    </div>
                    <small class="text-muted mt-2 d-block">Suba la captura de su transferencia (Máximo 2MB).</small>
                </div>
                @endif

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    @if($isLogged)
                    <a href="{{ route('web.carrito.index') }}" class="btn btn-link text-muted text-decoration-none">
                        <i class="fas fa-chevron-left me-1 fs-4"></i> Regresar al carrito
                    </a>
                    @endif
                    @if($isLogged)
                        <button type="submit" class="btn btn-dark btn-lg px-5 rounded-pill shadow fw-bold">Confirmar Pago</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            @if($isLogged)
                <div class="sticky-summary">
                    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; background: white;">
                        <h5 class="fw-bold mb-4">Resumen de Orden</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-bold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Envío:</span>
                            <span class="text-success fw-bold">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Total:</span>
                            <span class="h4 fw-bold" style="color: var(--primary-color);">${{ number_format($total, 2) }}</span>
                        </div>

                        <div class="alert alert-info py-2 small mb-0">
                            <i class="fas fa-info-circle me-1"></i> Una vez verificado el pago, procesaremos tu pedido.
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 bg-dark text-white p-4 shadow-sm" style="border-radius: 20px;">
                    <h5 class="fw-bold text-primary">¡Bienvenido!</h5>
                    <p class="small mb-0 opacity-75">Inicia sesión para poder seleccionar un método de pago, adjuntar tu comprobante y finalizar tu compra.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm mt-3 rounded-pill fs-4">Iniciar Sesión</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
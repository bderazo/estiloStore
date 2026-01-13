@extends('web.layouts.base')

@section('content')
    <div class="container py-5">
        {{-- Mensajes de Error y Éxito --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success shadow-sm">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-7">
                {{-- Detalles de la Reserva --}}
                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h4 class="fw-bold">Reserva: {{ $pedido->codigo_reserva }}</h4>
                    <p class="text-muted">Estado:
                        <span class="badge bg-dark">{{ strtoupper($pedido->estado) }}</span>
                    </p>
                    <hr>
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
                                                Color: {{ $detalle->variante->color->nombre ?? 'N/A' }} |
                                                Talla: {{ $detalle->variante->talla->nombre ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            {{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td class="fw-bold fs-5">TOTAL A PAGAR</td>
                                    <td class="text-end fw-bold text-primary fs-5">
                                        ${{ number_format($pedido->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Historial de Pagos --}}
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h5 class="fw-bold mb-3">Historial de Abonos / Vauchers</h5>
                    @if ($pedido->pagos->isEmpty())
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-receipt fa-2x mb-2 opacity-25"></i>
                            <p class="small">No has registrado abonos todavía.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($pedido->pagos as $pago)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <small
                                            class="d-block text-muted">{{ $pago->created_at->format('d/m/Y H:i') }}</small>
                                        <strong class="fs-5">${{ number_format($pago->monto, 2) }}</strong>
                                        <a href="{{ asset('storage/' . $pago->comprobante_ruta) }}" target="_blank"
                                            class="ms-2 btn btn-sm btn-outline-secondary py-0 fs-4">
                                            <i class="fas fa-image"></i> Ver
                                        </a>
                                    </div>
                                    <span
                                        class="badge rounded-pill @if ($pago->estado == 'aprobado') bg-success @elseif($pago->estado == 'rechazado') bg-danger @else bg-warning text-dark @endif">
                                        {{ ucfirst($pago->estado) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Formulario Lateral (Sticky) --}}
            <div class="col-lg-5">
                {{-- El z-index: 1 asegura que el menú principal (Navbar) siempre gane --}}
                {{-- El top: 110px evita que el card toque el borde superior o el menú --}}
                <div class="card border-0 shadow-sm p-4 rounded-4 sticky-top"
                    style="top: 110px; z-index: 1; background-color: white;">
                    <h5 class="fw-bold">Registrar Nuevo Abono</h5>
                    <p class="small text-muted">Sube una captura de tu transferencia o depósito.</p>

                    <form action="{{ route('web.pedidos.subir_pago', $pedido->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Monto del Abono</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="monto" class="form-control" placeholder="0.00"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Imagen del Comprobante</label>
                            <input type="file" name="comprobante" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Enviar para Validación</button>
                    </form>

                    <div class="alert alert-info mt-4 small border-0">
                        <i class="fas fa-info-circle"></i> Los abonos serán revisados por un técnico. Una vez aprobados, se
                        verán reflejados en tu saldo.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

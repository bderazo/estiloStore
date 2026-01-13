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
                            <span class="badge @if($p->estado == 'pendiente') bg-warning @elseif($p->estado == 'completado') bg-success @else bg-secondary @endif">
                                {{ ucfirst($p->estado) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('web.pedidos.show', $p->id) }}" class="btn btn-sm btn-dark">Ver Detalles / Pagar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('adminlte::page')

@section('title', 'Pagos Recibidos')

@push('css')
<style>
    .metric-card {
        border-radius: 14px;
        padding: 20px;
        background: var(--ton-beige-bg, #F5ECE3);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: .2s ease-in-out;
    }
    .metric-card:hover {
        transform: translateY(-3px);
    }
    .metric-icon {
        font-size: 26px;
        color: var(--ton-primary, #5D2728);
    }
    .table-tonalli thead {
        background: var(--ton-primary, #5D2728);
        color: #fff;
    }
    .badge-metodo {
        padding: 6px 10px;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    <h1 class="mb-4 fw-bold">
        <i class="fas fa-cash-register"></i> Pagos Recibidos
    </h1>

    {{-- ======================== --}}
    {{-- TARJETAS DE MÉTRICAS --}}
    {{-- ======================== --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon"><i class="fas fa-coins"></i></div>
                <h5 class="mt-2 mb-0">Total Cobrado</h5>
                <h3 class="fw-bold mt-1">${{ number_format($totalCobrado, 2) }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h5 class="mt-2 mb-0">Propinas</h5>
                <h3 class="fw-bold mt-1">${{ number_format($totalPropinas, 2) }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon"><i class="fas fa-wallet"></i></div>
                <h5 class="mt-2 mb-0">Total General</h5>
                <h3 class="fw-bold mt-1">${{ number_format($totalGeneral, 2) }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h5 class="mt-2 mb-0">Cambio Entregado</h5>
                <h3 class="fw-bold mt-1">${{ number_format($cambioTotal, 2) }}</h3>
            </div>
        </div>

    </div>


    {{-- ======================== --}}
    {{-- TARJETA: TOTAL POR MÉTODO --}}
    {{-- ======================== --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light">
            <h5 class="m-0 fw-bold">
                <i class="fas fa-chart-pie"></i> Totales por Método de Pago
            </h5>
        </div>
        <div class="card-body">

            <div class="row">
                @foreach($totalPorMetodo as $m)
                    <div class="col-md-3 mb-3">
                        <div class="metric-card p-3">
                            <h6 class="fw-bold text-capitalize">{{ $m->metodo }}</h6>
                            <h4 class="fw-bold">${{ number_format($m->total, 2) }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>


    {{-- ======================== --}}
    {{-- TABLA DE PAGOS --}}
    {{-- ======================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 fw-bold"><i class="fas fa-list"></i> Historial de Pagos</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-tonalli table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>#Pago</th>
                        <th>Cuenta</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Método</th>
                        <th>Monto</th>
                        <th>Referencia</th>
                        <th>Fecha</th>
                        <th>Cajero</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $p)
                    <tr>
                        <td>{{ $p->id }}</td>

                        <td>#{{ $p->cuenta->id }}</td>

                        <td>#{{ $p->cuenta->pedido->id }}</td>

                        <td>
                            @if($p->cuenta->tipo == 'comensal')
                                Persona {{ $p->cuenta->comensal->numero ?? '' }}
                            @else
                                Completa
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-metodo 
                                @if($p->metodo=='efectivo') bg-success
                                @elseif($p->metodo=='tarjeta') bg-primary
                                @elseif($p->metodo=='transferencia') bg-info
                                @else bg-secondary @endif">
                                {{ ucfirst($p->metodo) }}
                            </span>
                        </td>

                        <td>${{ number_format($p->monto, 2) }}</td>

                        <td>{{ $p->referencia ?? '—' }}</td>

                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>

                        <td>{{ $p->recibidoPor->name ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-center">
            {{ $pagos->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection

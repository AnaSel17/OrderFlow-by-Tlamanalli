@extends('adminlte::page')

@section('title', 'Dashboard Mesero')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
<style>
    .card-tonalli {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--ton-border);
        padding: 18px;
        box-shadow: 0 3px 7px var(--ton-shadow);
    }

    .stat-box {
        padding: 18px;
        border-radius: 14px;
        color: white;
        font-weight: 600;
        box-shadow: 0 3px 5px rgba(0,0,0,0.16);
    }

    .stat-value {
        font-size: 1.8rem;
    }

    .stat-title {
        opacity: .9;
        letter-spacing: .5px;
    }

    .chart-container {
        height: 260px;
    }

    .pedido-card {
        border-left: 6px solid var(--ton-primary);
    }
</style>
@endpush


@section('content')
<div class="container-fluid px-4 py-3">

    <h2 class="text-center mb-4 card-title-custom">🧑‍🍳 Dashboard — Mesero</h2>

    {{-- ================= TARJETAS SUPERIORES ================= --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-box" style="background:#5D2728;">
                <div class="stat-title">Pedidos activos</div>
                <div class="stat-value">{{ $pedidosActivos }}</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box" style="background:#1E8E7C;">
                <div class="stat-title">Pedidos listos</div>
                <div class="stat-value">{{ $pedidosListos }}</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box" style="background:#EE782B;">
                <div class="stat-title">Mesas asignadas</div>
                <div class="stat-value">{{ $mesasAsignadas }}</div>
            </div>
        </div>

    </div>

{{-- ================= SELECTOR DE FECHA ================= --}}
<div class="card card-tonalli mb-4">
    <h5 class="mb-3">📅 Seleccionar día</h5>

    <form method="GET" action="{{ route('dashboard.mesero') }}" class="d-flex gap-3">
        <input 
            type="date" 
            name="fecha" 
            class="form-control"
            value="{{ request('fecha', now()->toDateString()) }}"
        >

        <button class="btn btn-tonalli" style="background:#5D2728; color:white;">
            Filtrar
        </button>
    </form>
</div>

    {{-- ================= GRÁFICA DE ACTIVIDAD ================= --}}
    <div class="card card-tonalli mb-4">
        <h5 class="mb-3">⚡ Actividad del día (Pedidos por hora)</h5>
        <div class="chart-container">
            <canvas id="actividadChart"></canvas>
        </div>
    </div>


    {{-- ================= MIS PEDIDOS ================= --}}
    <div class="card card-tonalli">
        <h5 class="mb-3">🧾 Pedidos activos</h5>

        @forelse ($pedidosHoy as $pedido)
            <div class="p-3 mb-2 pedido-card rounded shadow-sm bg-white d-flex justify-content-between">
                <div>
                    <strong>Pedido #{{ $pedido->id }}</strong><br>
                    <small>Mesa(s): {{ $pedido->mesas_texto }}</small><br>
                    <small>Estado: {{ ucfirst(str_replace('_',' ', $pedido->estado)) }}</small>
                </div>

                <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>

                    <a href="{{ route('pedidos.edit', $pedido) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-utensils"></i>
                    </a>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">Sin pedidos activos.</p>
        @endforelse
    </div>

</div>
@endsection


@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('actividadChart'), {
    type: 'line',
    data: {
        labels: @json($labelsHoras),
        datasets: [{
            label: 'Pedidos',
            data: @json($dataHoras),
            borderColor: '#5D2728',
            backgroundColor: 'rgba(93,39,40,0.15)',
            borderWidth: 3,
            tension: 0.3,
            fill: true,
        }]
    },
    options: {
        plugins: { legend: { display: false }},
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>
@endpush

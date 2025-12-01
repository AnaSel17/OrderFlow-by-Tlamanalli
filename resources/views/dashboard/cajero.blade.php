@extends('adminlte::page')

@section('title', 'Dashboard Cajero')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
<style>
    .card-tonalli {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--ton-border);
        box-shadow: 0 3px 7px var(--ton-shadow);
        padding: 18px;
    }

    .stat-box {
        border-radius: 14px;
        padding: 20px;
        color: white;
        font-weight: 600;
        box-shadow: 0 3px 5px rgba(0,0,0,0.15);
    }

    .stat-title {
        font-size: .9rem;
        letter-spacing: .5px;
        opacity: .9;
    }

    .stat-value {
        font-size: 1.8rem;
        margin-top: 5px;
    }

    .chart-container {
        height: 300px;
    }
</style>
@endpush


@section('content')
<div class="container-fluid px-4 py-3">

    <h2 class="text-center mb-4 card-title-custom">💰 Dashboard — Cajero</h2>

    {{-- ======================= FILTRO POR FECHA ======================= --}}
<form method="GET" action="{{ route('dashboard.cajero') }}" class="mb-4">
    <div class="row justify-content-start align-items-end g-2">
        
        <div class="col-auto">
            <label for="fecha" class="form-label">Seleccionar fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control"
                   value="{{ request('fecha', now()->toDateString()) }}">
        </div>

        <div class="col-auto">
            <button class="btn btn-primary mt-4" style="background:#5D2728; border:none;">
                Filtrar
            </button>
        </div>

    </div>
</form>


    {{-- ======================= TARJETAS SUPERIORES ======================= --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-box" style="background:#5D2728;">
                <div class="stat-title">Ventas de hoy</div>
                <div class="stat-value">${{ number_format($ventasHoy, 2) }}</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box" style="background:#1E8E7C;">
                <div class="stat-title">Total cobrado hoy</div>
                <div class="stat-value">${{ number_format($totalCobrado, 2) }}</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box" style="background:#EE782B;">
                <div class="stat-title">Pedidos listos para cobrar</div>
                <div class="stat-value">{{ $listosParaCobrar }}</div>
            </div>
        </div>

    </div>


    {{-- ======================= GRÁFICO DE VENTAS POR HORA ======================= --}}
    <div class="card card-tonalli mb-4">
        <h5 class="mb-3">📊 Ventas por hora (Hoy)</h5>
        <div class="chart-container">
            <canvas id="ventasHoraChart"></canvas>
        </div>
    </div>


    {{-- ======================= ÚLTIMOS PAGOS ======================= --}}
    <div class="card card-tonalli">
        <h5 class="mb-3">🧾 Últimos pagos registrados</h5>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Pedido</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Hora</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ultimosPagos as $pago)
                    <tr>
                        <td>{{ $pago->cuenta->pedido_id }}</td>
                        <td>${{ number_format($pago->monto, 2) }}</td>
                        <td>{{ ucfirst($pago->metodo) }}</td>
                        <td>{{ $pago->created_at->format('H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            No hay pagos registrados hoy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>
@endsection


{{-- ======================= JS GRÁFICAS ======================= --}}
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('ventasHoraChart'), {
    type: 'bar',
    data: {
        labels: @json($labelsHoras),
        datasets: [{
            label: 'Ventas ($)',
            data: @json($dataHoras),
            backgroundColor: 'rgba(93,39,40,0.25)',
            borderColor: '#5D2728',
            borderWidth: 2,
            borderRadius: 6
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => '$' + v }
            }
        }
    }
});
</script>
@endpush

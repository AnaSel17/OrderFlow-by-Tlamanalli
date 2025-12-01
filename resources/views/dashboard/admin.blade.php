@extends('adminlte::page')

@section('title', 'Dashboard Administrador')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom-admin.css') }}">
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
        font-size: 1.7rem;
        margin-top: 5px;
    }

    .chart-container {
        height: 320px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <h2 class="text-center mb-4 card-title-custom">📊 Dashboard — Administrador</h2>

    {{-- ========================== TARJETAS ========================== --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stat-box" style="background:#5D2728">
                <div class="stat-title">Ventas de hoy</div>
                <div class="stat-value">${{ number_format($ventasHoy, 2) }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box" style="background:#8C463F">
                <div class="stat-title">Ventas últimos 7 días</div>
                <div class="stat-value">${{ number_format($ventasSemana, 2) }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box" style="background:#1E8E7C">
                <div class="stat-title">Pedidos activos</div>
                <div class="stat-value">{{ $pedidosActivos }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box" style="background:#EE782B">
                <div class="stat-title">Mesas ocupadas</div>
                <div class="stat-value">{{ $mesasOcupadas }} / {{ $totalMesas }}</div>
            </div>
        </div>

    </div>

    {{-- ========================== GRÁFICA 7 DÍAS ========================== --}}
    <div class="card card-tonalli mb-4">
        <h5 class="mb-3">📅 Ventas últimos 7 días</h5>
        <div class="chart-container">
            <canvas id="chart7dias"></canvas>
        </div>
    </div>

    {{-- ========================== PRODUCTOS MÁS VENDIDOS ========================== --}}
    <div class="card card-tonalli mb-4">
        <h5 class="mb-3">🍽 Productos más vendidos</h5>
        <div class="chart-container">
            <canvas id="chartProductos"></canvas>
        </div>
    </div>

    {{-- ========================== INVENTARIO BAJO ========================== --}}
    <div class="card card-tonalli">
        <h5 class="mb-3">⚠ Inventario Bajo</h5>

        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th>Stock Actual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventarioBajo as $item)
                    <tr>
                        <td>{{ $item->producto->nombre }}</td>
                        <td>{{ $item->cantidad }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">No hay productos con inventario bajo 🎉</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

{{-- ========================== JS GRÁFICAS ========================== --}}
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ---------- Ventas últimos 7 días ----------
    new Chart(document.getElementById('chart7dias'), {
        type: 'line',
        data: {
            labels: @json($labels7dias),
            datasets: [{
                label: 'Ventas ($)',
                data: @json($data7dias),
                borderColor: '#5D2728',
                backgroundColor: 'rgba(93,39,40,0.25)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
            }]
        }
    });

    // ---------- Productos más vendidos ----------
    new Chart(document.getElementById('chartProductos'), {
        type: 'bar',
        data: {
            labels: @json($labelsProductos),
            datasets: [{
                label: 'Vendidos ($)',
                data: @json($dataProductos),
                backgroundColor: [
                    '#5D2728', '#8C463F', '#1E8E7C', '#EE782B', '#947151'
                ],
                borderRadius: 8,
            }]
        }
    });
</script>
@endpush

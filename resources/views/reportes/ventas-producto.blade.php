@extends('adminlte::page')

@section('title', 'Ventas por Producto')
<style>
    .chart-container {
        max-width: 700px;        /* ancho máximo */
        margin: 0 auto;          /* centrado */
        padding: 20px;
    }

    .chart-canvas {
        max-height: 320px;       /* 🔥 altura más baja */
    }
</style>

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
<style>
    .card-tonalli {
        background: #fff;
        border: 1px solid var(--ton-border);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-5 py-4">

    <h3 class="card-title-custom text-center mb-4">📦 Ventas por Producto</h3>

    {{-- FILTRO --}}
    <form method="GET" class="mb-4 d-flex justify-content-center gap-3">
        <input type="date" name="fecha" value="{{ $fecha }}" class="form-control w-auto">
        <button class="btn btn-primary">
            <i class="fas fa-search"></i> Consultar
        </button>
    </form>

    {{-- GRÁFICA --}}
    <div class="chart-container">
        <canvas id="ventasProductoChart"></canvas>
    </div>

    {{-- TABLA --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Detalle de ventas por producto</h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad vendida</th>
                        <th>Total vendido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventas as $v)
                    <tr>
                        <td>{{ $v->nombre }}</td>
                        <td>{{ $v->cantidad_total }}</td>
                        <td>${{ number_format($v->total_vendido, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            No hay ventas este día.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('ventasProductoChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Ventas ($)',
            data: @json($data),

            // 🎨 PALETA PASTEL TONALLI (MULTICOLOR)
            backgroundColor: [
                'rgba(160, 214, 194, 0.85)',  // Verde pastel Tonalli
                'rgba(244, 194, 159, 0.85)',  // Durazno pastel
                'rgba(175, 200, 228, 0.85)',  // Azul pastel
                'rgba(231, 165, 154, 0.85)',  // Coral pastel
                'rgba(217, 184, 168, 0.85)',  // Beige rosado
                'rgba(200, 170, 190, 0.85)',  // Lila pastel
                'rgba(255, 220, 180, 0.85)',  // Amarillo suave
            ],

            borderColor: 'rgba(93, 39, 40, 1)',     // Café oscuro Tonalli
            borderWidth: 2,
            borderRadius: 10,

            hoverBackgroundColor: [
                'rgba(160, 214, 194, 1)',
                'rgba(244, 194, 159, 1)',
                'rgba(175, 200, 228, 1)',
                'rgba(231, 165, 154, 1)',
                'rgba(217, 184, 168, 1)',
                'rgba(200, 170, 190, 1)',
                'rgba(255, 220, 180, 1)',
            ],
        }]
    },

    options: {
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#5D2728',
                titleColor: '#fff',
                bodyColor: '#fff',
                cornerRadius: 8,
                padding: 12
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#2E221B',
                    font: { size: 14 }
                },
                grid: { display: false }
            },
            y: {
                ticks: {
                    color: '#2E221B',
                    font: { size: 14 },
                    callback: function(value) {
                        return '$' + value;
                    }
                },
                beginAtZero: true
            }
        }
    }
});

</script>
@endpush

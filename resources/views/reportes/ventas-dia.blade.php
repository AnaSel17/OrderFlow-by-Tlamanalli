@extends('adminlte::page')

@section('title', 'Ventas por Día')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
<style>
    .resumen-box {
        background: var(--ton-beige-bg);
        padding: 18px;
        border-radius: 12px;
        border: 1px solid var(--ton-border);
        box-shadow: 0 2px 4px var(--ton-shadow);
        text-align: center;
        font-weight: 600;
        color: var(--ton-brown-dark);
    }

    .resumen-total {
        font-size: 1.8rem;
        color: var(--ton-primary);
        margin-top: 10px;
        font-weight: 700;
    }

    .filter-card {
        background: white;
        border-radius: 14px;
        border: 1px solid var(--ton-border);
        padding: 20px;
        box-shadow: 0 2px 5px var(--ton-shadow);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-5 py-4">

    <h3 class="card-title-custom text-center mb-4">VENTAS POR DÍA</h3>

    {{-- FILTRO --}}
    <div class="filter-card mb-4">
        <form method="GET" action="{{ route('reportes.ventas-dia') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Selecciona una fecha:</label>
                <input type="date" name="fecha" class="form-control" value="{{ $fecha }}">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Consultar
                </button>
            </div>
        </form>
    </div>

    {{-- RESUMEN --}}
    <div class="resumen-box mb-4">
        Total vendido el <strong>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</strong>
        <div class="resumen-total">
            ${{ number_format($ventas, 2) }}
        </div>
    </div>

    
    {{-- TABLA DE PEDIDOS --}}
    <div class="card card-outline card-primary mt-4">
        <div class="card-header">
            <h3 class="card-title">Listado de ventas realizadas</h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th># Pedido</th>
                        <th>Mesa</th>
                        <th>Mesero</th>
                        <th>Total</th>
                        <th>Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pedidos as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->mesas_texto }}</td>
                        <td>{{ $p->usuario->name ?? '-' }}</td>
                        <td>${{ number_format($p->total, 2) }}</td>
                        <td>{{ $p->created_at->format('H:i') }}</td>

                        <td>
                            <a href="{{ route('pedidos.show', $p) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            No se registraron ventas este día.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

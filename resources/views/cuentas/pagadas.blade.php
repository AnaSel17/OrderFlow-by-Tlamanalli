@extends('adminlte::page')

@section('title', 'Cuentas Pagadas')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid px-5 py-4">

    <h3 class="card-title-custom text-center mb-4">
        <i class="fas fa-wallet"></i> CUENTAS PAGADAS
    </h3>

    {{-- Mensajes --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Si no hay cuentas pagadas --}}
    @if ($cuentas->isEmpty())
        <div class="alert alert-info text-center fw-bold py-4">
            No hay cuentas pagadas registradas.
        </div>
    @endif

    {{-- Lista de cuentas pagadas --}}
    <div class="row g-4">
        @foreach ($cuentas as $cuenta)
            <div class="col-lg-4 col-md-6">

                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header text-white" style="background-color: var(--ton-success)">
                        <strong>
                            @if ($cuenta->tipo === 'completa')
                                Cuenta Completa
                            @else
                                Persona {{ $cuenta->comensal->numero ?? '' }}
                            @endif
                        </strong>
                    </div>

                    <div class="card-body">

                        {{-- INFO GENERAL --}}
                        <p class="mb-1"><strong>Pedido:</strong> #{{ $cuenta->pedido->id }}</p>

                        <p class="mb-1"><strong>Mesero:</strong> {{ $cuenta->usuario->name ?? 'N/A' }}</p>

                        <p class="mb-1"><strong>Fecha:</strong>
                            {{ $cuenta->created_at->format('d/m/Y H:i') }}
                        </p>

                        <hr>

                        {{-- RESUMEN DE MONTOS --}}
                        <p class="mb-1"><strong>Subtotal:</strong>
                            ${{ number_format($cuenta->subtotal, 2) }}
                        </p>

                        <p class="mb-1"><strong>Propina:</strong>
                            ${{ number_format($cuenta->propina, 2) }}
                        </p>

                        <p class="mb-1"><strong>Descuento:</strong>
                            ${{ number_format($cuenta->descuento, 2) }}
                        </p>

                        <p class="fw-bold mt-2 fs-5">
                            Total: <span class="text-success">${{ number_format($cuenta->total, 2) }}</span>
                        </p>

                        <hr>

                        {{-- LISTA DE PAGOS --}}
                        <h6 class="fw-bold">Pagos realizados:</h6>

                        @foreach ($cuenta->pagos as $pago)
                            <div class="border rounded p-2 mb-2">
                                <strong>{{ ucfirst($pago->metodo) }}</strong>
                                <span class="float-end">
                                    ${{ number_format($pago->monto, 2) }}
                                </span>

                                @if ($pago->referencia)
                                    <br>
                                    <small class="text-muted">Ref: {{ $pago->referencia }}</small>
                                @endif
                            </div>
                        @endforeach

                        <div class="d-grid mt-3">
                            <a href="{{ route('pedidos.ticket', $cuenta->pedido_id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-receipt"></i> Ver Ticket
                            </a>
                        </div>

                    </div>{{-- card-body --}}
                </div>{{-- card --}}
            </div>{{-- col --}}
        @endforeach
    </div>{{-- row --}}

</div>
@endsection

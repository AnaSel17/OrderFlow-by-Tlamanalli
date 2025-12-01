@extends('adminlte::page')

@section('title', 'Tickets Emitidos')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')

<div class="container-fluid px-5 py-4">

    <h3 class="card-title-custom text-center mb-4">
        <i class="fas fa-receipt"></i> TICKETS EMITIDOS
    </h3>

    @if ($tickets->isEmpty())
        <div class="alert alert-info text-center fw-bold py-4">
            No hay tickets emitidos aún.
        </div>
    @endif

    <div class="row g-4">
        @foreach ($tickets as $ticket)
            <div class="col-lg-4 col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    {{-- HEADER --}}
                    <div class="card-header text-white" style="background-color: var(--ton-primary);">
                        <strong>
                            Ticket —
                            @if($ticket->tipo === 'completa')
                                Cuenta Completa
                            @else
                                Persona {{ $ticket->comensal->numero ?? '' }}
                            @endif
                        </strong>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">

                        <p class="mb-1">
                            <strong>Pedido:</strong> #{{ $ticket->pedido->id }}
                        </p>

                        <p class="mb-1">
                            <strong>Mesero:</strong>
                            {{ $ticket->usuario->name ?? 'N/A' }}
                        </p>

                        <p class="mb-1">
                            <strong>Fecha:</strong>
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </p>

                        <hr>

                        <p class="mb-1">
                            Subtotal:
                            <span class="float-end">${{ number_format($ticket->subtotal, 2) }}</span>
                        </p>

                        <p class="mb-1">
                            Propina:
                            <span class="float-end">${{ number_format($ticket->propina, 2) }}</span>
                        </p>

                        <p class="fw-bold fs-5 mt-2">
                            Total:
                            <span class="text-success float-end">
                                ${{ number_format($ticket->total, 2) }}
                            </span>
                        </p>

                        <hr>

                        <h6 class="fw-bold">Pagos:</h6>

                        @foreach ($ticket->pagos as $pago)
                            <div class="border rounded p-2 mb-2">
                                <strong>{{ ucfirst($pago->metodo) }}</strong>
                                <span class="float-end">${{ number_format($pago->monto, 2) }}</span>

                                @if($pago->referencia)
                                    <br>
                                    <small class="text-muted">Ref: {{ $pago->referencia }}</small>
                                @endif
                            </div>
                        @endforeach

                        <div class="d-grid mt-3">
                            <a href="{{ route('pedidos.ticket', $ticket->pedido->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-print"></i> Ver / Imprimir Ticket
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection

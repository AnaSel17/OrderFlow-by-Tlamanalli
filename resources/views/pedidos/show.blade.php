@extends('adminlte::page')

@section('title', "Pedido #{$pedido->id}")

@section('content')
<div class="container-fluid py-4 px-4">

    <h1 class="mb-4">
        <i class="fas fa-utensils"></i> Pedido #{{ $pedido->id }}
    </h1>

    {{-- Mensajes --}}
    @foreach (['success','info','error'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg === 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach


    {{-- ============================================= --}}
    {{-- INFORMACIÓN GENERAL DEL PEDIDO --}}
    {{-- ============================================= --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong><i class="fas fa-info-circle"></i> Información del pedido</strong>
        </div>
        <div class="card-body">
            <strong>Mesas:</strong> {{ $pedido->mesas_texto }} <br>
            <strong>Mesero:</strong> {{ $pedido->usuario->name }} <br>
            <strong>Comensales:</strong> {{ $pedido->comensales->count() }} <br>
            <strong>Estado:</strong> {!! $pedido->estado_texto !!} <br>
            <strong>Total:</strong> ${{ number_format($pedido->total, 2) }}
        </div>
    </div>


    {{-- ============================================= --}}
    {{-- SI NO HAY CUENTAS → INVITAR A COBRAR --}}
    {{-- ============================================= --}}
    @if ($cuentas->isEmpty())
        <div class="alert alert-info text-center fw-bold">
            Aún no hay cuentas generadas para este pedido.
            <br>
            <a href="{{ route('pedidos.cobrar', $pedido) }}" class="btn btn-primary mt-2">
                <i class="fas fa-cash-register"></i> Ir a cobrar
            </a>
        </div>
        @php return; @endphp
    @endif


    {{-- ============================================= --}}
    {{-- CUENTAS GENERADAS --}}
    {{-- ============================================= --}}
    <h4 class="mb-3"><i class="fas fa-wallet"></i> Cuentas generadas</h4>

    @foreach ($cuentas as $cuenta)
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>
                    Cuenta 
                    @if($cuenta->tipo === 'completa')
                        Completa
                    @else
                        Persona {{ $cuenta->comensal->numero ?? '' }}
                    @endif
                </strong>
            </div>

            <div class="card-body">

                {{-- DATOS GENERALES --}}
                <p>
                    <strong>Subtotal:</strong> ${{ number_format($cuenta->subtotal, 2) }} <br>
                    <strong>Propina:</strong> ${{ number_format($cuenta->propina, 2) }} <br>
                    <strong>Total:</strong> <span class="text-success fw-bold">${{ number_format($cuenta->total, 2) }}</span><br>
                    <strong>Estado:</strong> {{ ucfirst($cuenta->estado) }}
                </p>


                {{-- LISTA DE DETALLES --}}
                <h6 class="fw-bold mt-3">Detalles:</h6>
                <ul class="list-group mb-3">
                    @foreach ($cuenta->detalles as $cd)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $cd->detalle->producto->nombre }}</span>
                            <span>${{ number_format($cd->subtotal_asignado, 2) }}</span>
                        </li>
                    @endforeach
                </ul>


                {{-- PAGOS --}}
                <h6 class="fw-bold mt-3">Pagos:</h6>

                @if ($cuenta->pagos->isEmpty())
                    <p class="text-muted fst-italic">Sin pagos registrados.</p>
                @else
                    <ul class="list-group">
                        @foreach ($cuenta->pagos as $pago)
                            <li class="list-group-item">
                                <strong>{{ ucfirst($pago->metodo) }}</strong>
                                — ${{ number_format($pago->monto, 2) }}
                                @if ($pago->referencia)
                                    <br><small class="text-muted">Ref: {{ $pago->referencia }}</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif


            </div>
        </div>
    @endforeach

</div>
@endsection

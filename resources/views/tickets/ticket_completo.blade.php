@extends('adminlte::page')
@push('css')
<style>
.ticket {
    width: 280px; /* tamaño tipo ticket */
    margin: auto;
    background: white;
    padding: 15px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    border: 1px solid #ddd;
}

.ticket h3, 
.ticket h5 {
    text-align: center;
    margin: 5px 0;
    font-weight: bold;
}

.ticket img.logo {
    display: block;
    margin: 0 auto 10px auto;
    width: 80px;
}

.ticket table {
    width: 100%;
}

.ticket table td {
    padding: 3px 0;
}

.ticket .desc {
    text-align: left;
}

.ticket .total {
    text-align: right;
}

.line {
    border-top: 1px dashed #333;
    margin: 10px 0;
}

.small {
    font-size: 12px;
}

.no-print {
    margin-top: 15px;
}

@media print {
    .no-print {
        display: none !important;
    }
    body {
        background: white;
    }
}
</style>
@endpush

@section('content')
<div class="ticket">

    {{-- LOGO --}}
    <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">

    <h3>Tonalli Café</h3>
    <h5>Ticket de Consumo</h5>

    <div class="line"></div>

    <p>
        <strong>Pedido:</strong> #{{ $pedido->id }} <br>
        <strong>Tipo:</strong>
            {{ $pedido->tipo ?? 'En mesa' }} <br>
        <strong>Mesero:</strong> {{ $pedido->usuario->name }} <br>
        <strong>Mesas:</strong> {{ $pedido->mesas_texto }} <br>
        <strong>Fecha:</strong> {{ $cuenta->created_at->format('d/m/Y H:i') }}
    </p>

    <div class="line"></div>

    <h5>🧾 Detalles</h5>

    <table>
        @foreach ($cuenta->detalles as $d)
            <tr>
                <td class="desc">
                    {{ $d->detalle->producto->nombre }}
                </td>
                <td class="total">
                    ${{ number_format($d->subtotal_asignado, 2) }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <p>
        Subtotal: <span class="text-right">${{ number_format($cuenta->subtotal, 2) }}</span><br>
        Propina: <span class="text-right">${{ number_format($cuenta->propina, 2) }}</span><br>
        <strong>Total:</strong> <span class="text-right">${{ number_format($cuenta->total, 2) }}</span>
    </p>

    {{-- CAMBIO SI EXISTE --}}
    @php
        $pagoEfectivo = $cuenta->pagos->where('metodo','efectivo')->sum('monto');
        $cambio = $pagoEfectivo - $cuenta->total;
    @endphp

    @if ($cambio > 0)
        <p><strong>Cambio:</strong> ${{ number_format($cambio, 2) }}</p>
    @endif

    <div class="line"></div>

    <h5>💳 Pagos</h5>
    @foreach ($cuenta->pagos as $p)
        <p>
            {{ ucfirst($p->metodo) }} —
            ${{ number_format($p->monto, 2) }} <br>
            @if($p->referencia)
                <small class="small">Ref: {{ $p->referencia }}</small><br>
            @endif
        </p>
    @endforeach

    <div class="line"></div>

    <p class="text-center small">
        Gracias por su visita 🙌  
        <br>
        ¡Vuelva pronto!
    </p>

    <button onclick="window.print()" class="btn btn-primary w-100 no-print">
        Imprimir Ticket
    </button>

</div>
@endsection

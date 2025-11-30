@extends('adminlte::page')

@section('content')
<div class="ticket">

    <h3>🍽 Tlamanali</h3>
    <h5>Ticket de Consumo</h5>

    <div class="line"></div>

    <p>
        <strong>Pedido:</strong> #{{ $pedido->id }} <br>
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

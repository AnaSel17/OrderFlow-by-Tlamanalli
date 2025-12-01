@extends('adminlte::page')

@section('title', 'Dashboard Cocinero')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">

<style>
    .card-tonalli {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--ton-border);
        padding: 14px;
        box-shadow: 0 3px 7px var(--ton-shadow);
    }

    .stat-box {
        padding: 18px;
        border-radius: 14px;
        color: #fff;
        font-weight: 700;
        font-size: 1.4rem;
        text-align: center;
        line-height: 1.2;
    }

    .pedido-item {
        border-left: 6px solid var(--ton-primary);
        background: #fff;
        padding: 12px;
        border-radius: 12px;
        margin-bottom: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.06);
    }

    .badge-estado {
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 8px;
    }

    .bg-pendiente { background: var(--bs-warning); }
    .bg-preparacion { background: var(--bs-info); }
    .bg-listo { background: var(--bs-success); }

    .timer {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ton-primary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <h2 class="text-center mb-4 card-title-custom">🍳 Dashboard — Cocina</h2>

    {{-- ================= TARJETAS RÁPIDAS ================= --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-box" style="background:#F4C29F;"> {{-- Amarillo pastel --}}
                Pendientes<br>
                {{ $pendientes }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box" style="background:#AFC8E4;"> {{-- Azul pastel --}}
                En preparación<br>
                {{ $enPreparacion }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box" style="background:#A0D6C2;"> {{-- Verde pastel --}}
                Listos<br>
                {{ $listos }}
            </div>
        </div>

    </div>


    {{-- ================= LISTA DE DETALLES ================= --}}
    <div class="card card-tonalli mb-4">
        <h5 class="mb-3">🧾 Ordenes en curso</h5>

        @forelse ($detalles as $d)
            <div class="pedido-item">

                <div class="d-flex justify-content-between">
                    <strong>
                        Mesa {{ $d->pedido->mesas_texto }} • 
                        {{ $d->producto->nombre }}
                    </strong>

                    {{-- Estado --}}
                    @if($d->estado === 'pendiente')
                        <span class="badge-estado bg-pendiente">Pendiente</span>
                    @elseif($d->estado === 'en_preparacion')
                        <span class="badge-estado bg-preparacion">En preparación</span>
                    @elseif($d->estado === 'listo')
                        <span class="badge-estado bg-listo">Listo</span>
                    @endif
                </div>

                {{-- Tiempo transcurrido --}}
                <div class="timer my-1" data-time="{{ $d->created_at }}">
                    ⏱ --
                </div>

                {{-- Acciones --}}
                <div class="d-flex gap-2 mt-2">

                    @if($d->estado === 'pendiente')
                        <form action="{{ route('pedidos.index') }}" method="POST">
                            @csrf @method('get')
                            <button class="btn btn-info btn-sm">Iniciar</button>
                        </form>
                    @endif

                    @if($d->estado === 'en_preparacion')
                        <form action="{{ route('detalles.listo', $d) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-success btn-sm">Listo</button>
                        </form>
                    @endif

                </div>
            </div>

        @empty
            <p class="text-center text-muted">No hay órdenes pendientes en cocina.</p>
        @endforelse
    </div>

</div>
@endsection


@push('js')
<script>
// Actualización de timers
document.querySelectorAll('.timer').forEach(el => {
    const inicio = new Date(el.dataset.time);

    function actualizar() {
        const ahora = new Date();
        const diff = Math.floor((ahora - inicio) / 1000);

        const min = Math.floor(diff / 60);
        const seg = diff % 60;

        el.textContent = `⏱ ${min}m ${seg}s`;
    }

    actualizar();
    setInterval(actualizar, 1000);
});
</script>
@endpush

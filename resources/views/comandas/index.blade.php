@extends('adminlte::page')

@section('title', 'Comandas en cocina')

@section('content')
<div class="container-fluid py-4 px-4">

    <h1 class="mb-4 text-dark">
        <i class="fas fa-concierge-bell"></i> Comandas en Cocina
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

    {{-- Sin comandas --}}
    @if ($comandas->isEmpty())
        <div class="alert alert-warning text-center fw-bold">
            ⚠️ No hay comandas activas en cocina por el momento.
        </div>
    @endif

    <div class="row">
        @foreach ($comandas as $comanda)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 mb-4">

                    {{-- ENCABEZADO --}}
                    <div class="card-header fw-bold bg-gradient
                        @if($comanda->estado === 'enviado_cocina') bg-info text-white
                        @elseif($comanda->estado === 'en_preparacion') bg-warning text-dark
                        @elseif($comanda->estado === 'listo') bg-success text-white
                        @endif">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>🧾 Comanda #{{ $comanda->id }}</span>
                            <small>{{ ucfirst(str_replace('_',' ',$comanda->estado)) }}</small>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- INFO GENERAL --}}
                        <p><strong>Mesas:</strong> {{ $comanda->pedido->mesas_texto ?? 'Sin mesa' }}</p>
                        <p><strong>Mesero:</strong> {{ $comanda->pedido->usuario->name ?? '—' }}</p>

                        {{-- PRODUCTOS AGRUPADOS --}}
                        @php
                            $detalles = $comanda->pedido->detalles->where('comanda_id', $comanda->id);
                            $grupos   = $detalles->groupBy('producto_id');
                        @endphp

                        @foreach ($grupos as $items)

                            <div class="mb-3 p-2 border rounded bg-light">

                                {{-- ENCABEZADO DEL PRODUCTO --}}
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $items->first()->producto->nombre }}</strong>

                                        {{-- NOTAS AGRUPADAS --}}
                                        @php
                                            $notasAgrupadas = $items
                                                ->groupBy('notas')
                                                ->map->sum('cantidad')
                                                ->filter(fn($count, $nota) => trim($nota) !== '');

                                            $totalNotas = $notasAgrupadas->sum();
                                        @endphp

                                        <small class="text-muted d-block mt-1">
                                            @if ($notasAgrupadas->isEmpty())
                                                Notas: —
                                            @else
                                                Notas ({{ $totalNotas }}):
                                                <ul class="mb-0 mt-1 ps-3">
                                                    @foreach ($notasAgrupadas as $nota => $cantidad)
                                                        <li>{{ $cantidad }} × {{ $nota }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </small>
                                    </div>

                                    <span class="badge bg-primary">
                                        Total: {{ $items->sum('cantidad') }}
                                    </span>
                                </div>

                                {{-- BOTONES INTELIGENTES DE GRUPO --}}
                                @php
                                    $hayEnviado    = $items->contains('estado', 'enviado_cocina');
                                    $hayPreparando = $items->contains('estado', 'en_preparacion');
                                    $todosListos   = $items->every(fn($d) => $d->estado === 'listo');
                                @endphp

                                <div class="mb-3 d-flex gap-2">

                                    {{-- 🔥 Preparar todo --}}
                                    @if($hayEnviado && !$todosListos)
                                        <form action="{{ route('detalles.preparar.grupo') }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="ids" value="{{ $items->pluck('id')->join(',') }}">
                                            <button class="btn btn-sm btn-warning w-100">
                                                🔥 Preparar todo
                                            </button>
                                        </form>
                                    @endif

                                    {{-- ✔ Listo todo --}}
                                    @if($hayPreparando)
                                        <form action="{{ route('detalles.listo.grupo') }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="ids" value="{{ $items->pluck('id')->join(',') }}">
                                            <button class="btn btn-sm btn-success w-100">
                                                ✔ Listo todo
                                            </button>
                                        </form>
                                    @endif

                                </div>

                                {{-- DETALLES INDIVIDUALES --}}
                                @foreach ($items as $detalle)
                                    <div class="p-2 mb-2 border rounded d-flex justify-content-between align-items-center">

                                        <div>
                                            <small class="text-muted">Cantidad: {{ $detalle->cantidad }}</small>
                                        </div>

                                        <span class="badge
                                            @if($detalle->estado === 'pendiente') bg-secondary
                                            @elseif($detalle->estado === 'en_preparacion') bg-warning
                                            @elseif($detalle->estado === 'listo') bg-success
                                            @endif">
                                            {{ ucfirst(str_replace('_',' ',$detalle->estado)) }}
                                        </span>

                                        <div class="ms-2">

                                            @if($detalle->estado === 'enviado_cocina')
                                                <form action="{{ route('detalles.preparar', $detalle->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-sm btn-warning">🔥 Preparar</button>
                                                </form>
                                            @endif

                                            @if($detalle->estado === 'en_preparacion')
                                                <form action="{{ route('detalles.listo', $detalle->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button class="btn btn-sm btn-success">✔ Listo</button>
                                                </form>
                                            @endif

                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        @endforeach

                        {{-- ESTADO GLOBAL --}}
                        @php
                            $total  = $comanda->detalles->where('comanda_id', $comanda->id);
                            $prep   = $total->where('estado','en_preparacion')->count();
                            $listos = $total->where('estado','listo')->count();
                        @endphp

                        @if ($listos === $total->count())
                            <button class="btn btn-success w-100 fw-bold" disabled>
                                🟢 Comanda lista para entregar
                            </button>
                        @elseif ($prep > 0)
                            <button class="btn btn-warning w-100 fw-bold" disabled>
                                🟡 En preparación…
                            </button>
                        @else
                            <button class="btn btn-info w-100 fw-bold" disabled>
                                🔵 Esperando inicio
                            </button>
                        @endif

                    </div>

                    {{-- PIE --}}
                    <div class="card-footer text-muted text-center small">
                        <i class="far fa-clock"></i>
                        Enviada: {{ $comanda->enviada_en ? $comanda->enviada_en->format('H:i') : '—' }}
                    </div>

                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

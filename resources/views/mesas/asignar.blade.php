@extends('adminlte::page')

@section('title', 'Asignar Mesa')

@push('css')
<link rel="stylesheet" href="{{ asset('css/tonalli.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- 🔹 Título principal --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1 class="m-0 text-dark">
            <i class="fas fa-chair"></i> Asignar Mesa
        </h1>
        <small class="text-muted">
            Selecciona una mesa disponible para comenzar un pedido.
        </small>
    </div>

    {{-- 🕒 Hora actual --}}
    <div class="d-flex align-items-center mb-4">
        <i class="fas fa-clock text-primary me-2"></i>
        <strong>Hora actual:</strong>
        <span class="ms-2">{{ $horaActual }}</span>
    </div>

    {{-- ✅ ALERTAS DE ÉXITO O ERROR --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! nl2br(e(session('success'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
        <div>
            {!! nl2br(e(session('error'))) !!}
        </div>
        @if(Str::contains(session('error'), 'sillas extra'))
            <button type="button" class="btn btn-light btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#modalSillas"
                onclick="abrirModalSillas('{{ Str::between(session('error'), '(IDs: ', ')') }}', 
                {{ Str::between(session('error'), 'asignar ', ' comensales') }})">
                <i class="fas fa-plus-circle text-success"></i> Agregar sillas
            </button>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- 🔍 Buscador de cantidad de personas --}}
    <form action="{{ route('mesas.asignar') }}" method="GET" class="mb-4 d-flex align-items-center gap-3">
        <label for="personas" class="fw-bold mb-0">
            <i class="fas fa-user-friends"></i> Personas:
        </label>
        <input type="number" name="personas" id="personas"
               class="form-control w-auto"
               min="1" max="20"
               value="{{ request('personas') }}"
               placeholder="Ej. 5">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Buscar
        </button>
    </form>

    {{-- 🧩 Sugerencias de combinación --}}
    @if($personas && !empty($sugerencias))
        <div class="alert alert-info mb-4">
            <h5><i class="fas fa-layer-group"></i> Sugerencias de combinación</h5>
            @foreach($sugerencias as $zonaNombre => $combos)
                <p class="fw-bold mt-3 mb-2">{{ $zonaNombre }}</p>
                @foreach($combos as $combo)
                    <form action="{{ route('mesas.asignarMesas') }}" method="POST" onsubmit="return confirmarAsignacion(this)">
                        @csrf
                        @foreach($combo['mesas'] as $id)
                            <input type="hidden" name="mesas[]" value="{{ $id }}">
                        @endforeach
                        {{-- Campo oculto para número de comensales --}}
                        <input type="hidden" name="num_comensales" value="">
                        <button type="submit"
                            class="btn btn-light border-success text-success btn-sm me-2 mb-2"
                            data-capacidad="{{ $combo['total_capacidad'] }}">
                            <i class="fas fa-chair"></i>
                            {{ implode(', ', array_map(fn($id) => 'M' . str_pad($id, 2, '0', STR_PAD_LEFT), $combo['mesas'])) }}
                            — Capacidad total: {{ $combo['total_capacidad'] }}
                        </button>
                    </form>
                @endforeach
            @endforeach
        </div>
    @elseif($personas)
        <div class="alert alert-warning mb-4">
            <i class="fas fa-exclamation-circle"></i>
            No se encontraron mesas o combinaciones para {{ $personas }} personas.
        </div>
    @endif

    {{-- 💺 Listado de zonas y mesas disponibles --}}
    @foreach ($zonas as $zona)
        <div class="card mb-4 border-{{ $zona->estaAbierta() ? 'success' : 'danger' }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group"></i> {{ $zona->nombre }}
                </h5>
                <span class="badge {{ $zona->estaAbierta() ? 'bg-success' : 'bg-danger' }}">
                    {{ $zona->estaAbierta() ? 'Abierta' : 'Cerrada' }}
                </span>
            </div>

            <div class="card-body">
                @if ($zona->estaAbierta())
                    <div class="d-flex flex-wrap gap-3">
                        @forelse ($zona->mesas as $mesa)
                            <form action="{{ route('mesas.asignarMesas') }}" method="POST" onsubmit="return confirmarAsignacion(this)">
                                @csrf
                                <input type="hidden" name="mesas[]" value="{{ $mesa->id }}">
                                {{-- Campo oculto para número de comensales --}}
                                <input type="hidden" name="num_comensales" value="">
                                <button type="submit"
                                    class="btn btn-success p-3 d-flex flex-column align-items-center"
                                    data-capacidad="{{ $mesa->capacidad + $mesa->sillas_extra }}">
                                    <i class="fas fa-chair fa-2x mb-2"></i>
                                    <span class="fw-bold">{{ $mesa->codigo }}</span>
                                    <small>{{ $mesa->capacidad + $mesa->sillas_extra }} personas</small>
                                </button>
                            </form>
                        @empty
                            <p class="text-muted mb-0">No hay mesas disponibles en esta zona.</p>
                        @endforelse
                    </div>
                @else
                    <p class="text-muted mb-0">Zona cerrada. No se pueden asignar mesas ahora.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- 🔹 Modal para agregar sillas extra -->
<div class="modal fade" id="modalSillas" tabindex="-1" aria-labelledby="modalSillasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('mesas.agregarSillas') }}" method="POST">
        @csrf
        <input type="hidden" name="mesa_id" id="modalMesaId">
        <input type="hidden" name="num_comensales" id="modalNumComensales">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalSillasLabel">
            <i class="fas fa-chair"></i> Agregar Sillas Extra
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">Indica cuántas sillas adicionales deseas agregar para esta mesa.</p>
          <div class="mb-3">
            <label for="cantidad" class="form-label fw-bold">Cantidad de sillas:</label>
            <input type="number" min="1" max="5" class="form-control" name="cantidad" id="cantidad" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-check"></i> Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarAsignacion(form) {

    const capacidad = parseInt(form.querySelector('button[type="submit"]').dataset.capacidad);

    Swal.fire({
        title: "¿Cuántos comensales hay en esta mesa?",
        input: "number",
        inputLabel: `Capacidad disponible: ${capacidad}`,
        inputAttributes: { min: 1 },
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {

        if (!result.isConfirmed) return;

        const num = parseInt(result.value, 10);

        if (!num || num <= 0) {
            Swal.fire({
                icon: "error",
                title: "Número inválido",
                text: "Debes ingresar un número válido."
            });
            return;
        }

        // 🛑 SI EXCEDE LA CAPACIDAD
        if (num > capacidad) {

            const diferencia = num - capacidad;

            // ❌ NO SE PUEDEN AGREGAR MÁS DE 2
            if (diferencia > 2) {
                Swal.fire({
                    icon: "warning",
                    title: "Capacidad insuficiente",
                    html: `
                        <p>La mesa solo admite <strong>${capacidad}</strong> personas.</p>
                        <p>No puedes agregar <strong>${diferencia}</strong> sillas, máximo 2 extras.</p>
                    `,
                    confirmButtonText: "Entendido"
                });
                return;
            }

            // ⚠️ SÍ SE PUEDE AGREGAR 1 O 2 → Mostramos opciones
            Swal.fire({
                icon: "info",
                title: "Capacidad insuficiente",
                html: `
                    <p>La mesa solo admite <strong>${capacidad}</strong> personas.</p>
                    <p>Faltan <strong>${diferencia}</strong> lugares.</p>
                    <p>¿Deseas agregar sillas extra?</p>
                `,
                showCancelButton: true,
                confirmButtonText: "Sí, agregar sillas",
                cancelButtonText: "Cancelar"
            }).then((r2) => {

                if (!r2.isConfirmed) return;

                // 👉 Elegir 1 o 2 sillas
                Swal.fire({
                    title: "¿Cuántas sillas deseas agregar?",
                    input: "select",
                    inputOptions: {
                        1: "Agregar 1 silla",
                        2: "Agregar 2 sillas"
                    },
                    inputPlaceholder: "Selecciona una opción",
                    showCancelButton: true,
                    confirmButtonText: "Continuar",
                    cancelButtonText: "Cancelar"
                }).then((r3) => {

                    if (!r3.isConfirmed) return;

                    const cantidad = parseInt(r3.value, 10);

                    // 🚀 Abrir tu modal original y enviar valores
                    abrirModalSillasDesdeJS(form, cantidad, num);
                });

            });

            return;
        }

        // ✔ Guardar valor si se ajusta a la capacidad
        form.querySelector('input[name="num_comensales"]').value = num;

        Swal.fire({
            title: `¿Asignar mesa a ${num} comensal(es)?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, asignar",
            cancelButtonText: "Cancelar"
        }).then((ok) => {
            if (ok.isConfirmed) form.submit();
        });

    });

    return false;
}

function abrirModalSillasDesdeJS(form, cantidadSillas, numComensales) {

    // Ajustar número total de comensales
    const totalComensales = numComensales;

    // Pasar número al input oculto
    form.querySelector('input[name="num_comensales"]').value = totalComensales;

    Swal.fire({
        icon: "success",
        title: "Sillas agregadas",
        html: `
            <p>Se agregarán <strong>${cantidadSillas}</strong> sillas adicionales.</p>
            <p>Total de comensales: <strong>${totalComensales}</strong></p>
        `,
        confirmButtonText: "Continuar"
    }).then(() => {
        form.submit();
    });
}




function abrirModalSillas(ids) {
    if (!ids) {
        alert("No se pudieron obtener los IDs de las mesas.");
        return;
    }

    // Si hay varias mesas, tomamos la primera (por simplicidad)
    const mesaIds = ids.split(',');
    const mesaId = mesaIds[0].trim();

    document.getElementById('modalMesaId').value = mesaId;

    // Mostramos el modal manualmente (por si falla el data-bs-toggle)
    const modal = new bootstrap.Modal(document.getElementById('modalSillas'));
    modal.show();
}

function abrirModalSillas(ids, numComensales = 1) {
    if (!ids) {
        alert("No se pudieron obtener los IDs de las mesas.");
        return;
    }

    const mesaIds = ids.split(',');
    const mesaId = mesaIds[0].trim();

    document.getElementById('modalMesaId').value = mesaId;

    // 🔹 Guardamos el número de comensales en un input oculto del modal
    let hidden = document.getElementById('modalNumComensales');
    if (!hidden) {
        hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'num_comensales';
        hidden.id = 'modalNumComensales';
        document.querySelector('#modalSillas form').appendChild(hidden);
    }
    hidden.value = numComensales;

    const modal = new bootstrap.Modal(document.getElementById('modalSillas'));
    modal.show();
}

</script>
@endpush

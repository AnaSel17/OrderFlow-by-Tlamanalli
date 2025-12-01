@extends('adminlte::page')

@section('title', 'Cobrar Pedido')

@section('content')
<div class="container-fluid py-4 px-4">

    <h1 class="mb-4">
        <i class="fas fa-cash-register"></i> Cobrar Pedido #{{ $pedido->id }}
    </h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Información del pedido --}}
    <div class="card mb-4">
        <div class="card-body">
            <strong>Mesas:</strong> {{ $pedido->mesas_texto }} <br>
            <strong>Mesero:</strong> {{ $pedido->usuario->name }} <br>
            <strong>Comensales:</strong> {{ $pedido->comensales->count() }} <br>
            <strong>Total pedido:</strong> ${{ number_format($pedido->total, 2) }}
        </div>
    </div>

    <form id="form_cobro" action="{{ route('pedidos.finalizarCobro', $pedido) }}" method="POST">
        @csrf
        @method('PATCH')

        <input type="hidden" name="tipo_cobro" id="tipo_cobro_input" value="completo">
        <input type="hidden" name="divisiones_compartidos" id="divisiones_compartidos">
        <input type="hidden" name="cobros_por_comensal" id="cobros_por_comensal">
        <input type="hidden" name="pagos_globales" id="pagos_globales">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Tipo de cobro --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de cobro:</label>
                            <select id="tipo_cobro" class="form-select">
                                <option value="completo" selected>Cuenta completa</option>
                                <option value="separado">Cuenta por comensal</option>
                            </select>
                        </div>

                        {{-- ================================================ --}}
                        {{-- SECCIÓN 1 — PLATILLOS COMPARTIDOS (solo en separado) --}}
                        {{-- ================================================ --}}
                        @php
                            $detallesCompartidos = $pedido->detalles->whereNull('comensal_id');
                        @endphp

                        <div id="seccion_compartidos" class="mb-4 d-none">
                            @if ($detallesCompartidos->count() > 0)
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white" 
                                    style="display:flex; justify-content:space-between; align-items:center;">
                                        <div>
                                        <h5 class="mb-0" style="margin:0;">
                                            <i class="fas fa-utensils"></i> Platillos para Compartir
                                        </h5>
                                        </div>
                                        <div>
                                            <button type="button"
                                                    id="btn_ocultar_compartidos"
                                                    class="btn btn-outline-light btn-sm"
                                                    style="color:white; border-color:white;">
                                                Ocultar sección de compartidos
                                            </button>

                                        </div>
                                    </div>
                                    
                                    <div class="card-body">

                                        @foreach ($detallesCompartidos as $detalle)
                                            <div class="border rounded p-3 mb-4 shadow-sm producto-compartido"
                                                 data-id="{{ $detalle->id }}"
                                                 data-total="{{ $detalle->precio_unitario * $detalle->cantidad }}"
                                                 data-nombre="{{ $detalle->producto->nombre }}">

                                                <h5 class="fw-bold mb-2">
                                                    📦 {{ $detalle->producto->nombre }}
                                                    <span class="text-muted">
                                                        ({{ $detalle->cantidad }} × ${{ number_format($detalle->precio_unitario, 2) }})
                                                        — ${{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}
                                                    </span>
                                                </h5>

                                                <label class="fw-bold mb-2">Método de división:</label>
                                                <div class="d-flex flex-wrap gap-3 mb-2">
                                                    <label class="me-3">
                                                        <input type="radio"
                                                               name="metodo_{{ $detalle->id }}"
                                                               value="50_50"
                                                               class="form-check-input metodo-radio"
                                                               data-id="{{ $detalle->id }}">
                                                        50/50
                                                    </label>

                                                    <label class="me-3">
                                                        <input type="radio"
                                                               name="metodo_{{ $detalle->id }}"
                                                               value="todos"
                                                               class="form-check-input metodo-radio"
                                                               data-id="{{ $detalle->id }}">
                                                        Entre todos
                                                    </label>

                                                    <label class="me-3">
                                                        <input type="radio"
                                                               name="metodo_{{ $detalle->id }}"
                                                               value="monto"
                                                               class="form-check-input metodo-radio"
                                                               data-id="{{ $detalle->id }}">
                                                        Por monto
                                                    </label>

                                                    <label class="me-3">
                                                        <input type="radio"
                                                               name="metodo_{{ $detalle->id }}"
                                                               value="100"
                                                               class="form-check-input metodo-radio"
                                                               data-id="{{ $detalle->id }}">
                                                        100% a alguien
                                                    </label>

                                                    <label class="me-3">
                                                        <input type="radio"
                                                               name="metodo_{{ $detalle->id }}"
                                                               value="porcentaje"
                                                               class="form-check-input metodo-radio"
                                                               data-id="{{ $detalle->id }}">
                                                        Por porcentajes
                                                    </label>
                                                </div>

                                                <div class="mt-3 p-3 border rounded bg-light resultado-division d-none"
                                                     id="resultado_{{ $detalle->id }}">
                                                </div>

                                                <button type="button"
                                                        class="btn btn-success mt-3 d-none btn-asignar-final"
                                                        data-id="{{ $detalle->id }}">
                                                    <i class="fas fa-user-check"></i> Asignar a comensales
                                                </button>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @else
                                <p class="text-muted">
                                    No hay platillos marcados como compartidos en este pedido.
                                </p>
                            @endif
                        </div>

                        {{-- ================================================ --}}
                        {{-- SECCIÓN 2 — COBRO POR COMENSAL (solo en separado) --}}
                        {{-- ================================================ --}}
                        <div class="card mb-4 d-none" id="seccion_por_comensal">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-users"></i> Cobro por Comensal
                                </h5>
                            </div>
                            <div class="card-body">

                                @foreach ($pedido->comensales as $com)
                                    <div class="border rounded p-3 mb-4 shadow-sm card-comensal"
                                         data-id="{{ $com->id }}"
                                         id="comensal_card_{{ $com->id }}">

                                        <h4 class="fw-bold mb-2">
                                            👤 Persona {{ $com->numero }}
                                        </h4>

                                        <div id="productos_comensal_{{ $com->id }}" class="mt-2 mb-2">
                                            <h6 class="fw-bold text-primary">Productos:</h6>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Subtotal:</strong>
                                            <span id="subtotal_{{ $com->id }}">$0.00</span>
                                        </div>

                                        {{-- PROPINAS INDIVIDUALES --}}

                                        <div class="mt-3">

                                            <label class="fw-bold">Propina:</label>

                                            <div class="input-group mb-2" style="max-width: 200px;">
                                                <span class="input-group-text">$</span>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control propina-com"
                                                    data-id="{{ $com->id }}"
                                                    id="propina_input_{{ $com->id }}"
                                                    value="0">
                                            </div>

                                            <div class="btn-group mb-3">
                                                <button type="button" class="btn btn-outline-secondary propina-btn-com"
                                                        data-id="{{ $com->id }}" data-value="0.05">5%</button>

                                                <button type="button" class="btn btn-outline-secondary propina-btn-com"
                                                        data-id="{{ $com->id }}" data-value="0.10">10%</button>

                                                <button type="button" class="btn btn-outline-secondary propina-btn-com"
                                                        data-id="{{ $com->id }}" data-value="0.15">15%</button>

                                                <button type="button" class="btn btn-outline-secondary propina-btn-com"
                                                        data-id="{{ $com->id }}" data-value="0.20">20%</button>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <strong>Total con propina:</strong>
                                            <span id="total_con_propina_{{ $com->id }}" class="fw-bold">$0.00</span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <strong>Faltante:</strong>
                                            <span id="faltante_com_{{ $com->id }}" class="fw-bold text-danger">$0.00</span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <strong>Total:</strong>
                                            <span id="total_{{ $com->id }}" class="fw-bold">$0.00</span>
                                        </div>

                                        <hr>

                                        <label class="fw-bold">Método de pago:</label>
                                        <select class="form-select metodo-com" data-id="{{ $com->id }}">
                                            <option>Selecciona el método de pago</option>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="tarjeta">Tarjeta</option>
                                            <option value="transferencia">Transferencia</option>
                                            <option value="mixto">Mixto</option>
                                        </select>

                                        <div class="mt-3 pago-simple d-none" id="pago_simple_{{ $com->id }}">
                                            <label class="fw-bold">Monto recibido:</label>
                                            <input type="number"
                                                step="0.01"
                                                class="form-control pago-simple-input"
                                                data-id="{{ $com->id }}"
                                                placeholder="$0.00">

                                            <div class="mt-2 cambio-row d-none" id="cambio_row_{{ $com->id }}">
                                                <strong>Cambio:</strong>
                                                <span id="cambio_{{ $com->id }}" class="fw-bold text-success">$0.00</span>
                                            </div>

                                        </div>


                                        <div class="mt-3 pagos-mixtos d-none" id="pagos_com_{{ $com->id }}">
                                            <label class="fw-bold">Pagos:</label>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary mb-2 add_pago_com"
                                                    data-id="{{ $com->id }}">
                                                + Añadir pago
                                            </button>
                                            <div id="pagos_list_{{ $com->id }}"></div>
                                        </div>

                                        <hr>

                                        <label class="fw-bold">Paga por:</label>
                                        <select class="form-select paga-por" data-id="{{ $com->id }}">
                                           
                                            <option value="{{ $com->id }}" selected>Esta persona</option>
                                            @foreach ($pedido->comensales as $otro)
                                                @if ($otro->id != $com->id)
                                                    <option value="{{ $otro->id }}">Persona {{ $otro->numero }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                         <button type="button"
                                                    class="btn btn-primary mt-3 w-100 btn-cobrar-comensal"
                                                    data-id="{{ $com->id }}">
                                                <i class="fas fa-check"></i> Cobrar a este comensal
                                            </button>

                                    </div>
                                @endforeach

                            </div>
                        </div>

                        {{-- ================================================ --}}
                        {{-- SECCIÓN CUENTA COMPLETA (tabla + pagos globales) --}}
                        {{-- ================================================ --}}
                        <div id="seccion_cobro_completo">
                            {{-- Tabla de productos --}}
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Comensal</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedido->detalles as $detalle)
                                        <tr  
                                            data-comensal="{{ $detalle->comensal_id ?? 'general' }}"
                                            data-total-linea="{{ $detalle->precio_unitario * $detalle->cantidad }}"
                                            data-detalle-id="{{ $detalle->id }}"
                                            class="fila-detalle"
                                        >
                                            <td style="width:40px;">
                                                @if ($detalle->estado !== 'pagado')
                                                    <input type="checkbox"
                                                        name="detalle_ids[]"
                                                        value="{{ $detalle->id }}"
                                                        class="form-check-input detalle-check"
                                                        data-precio="{{ $detalle->precio_unitario * $detalle->cantidad }}">
                                                @endif
                                            </td>

                                            <td>{{ $detalle->producto->nombre }}</td>
                                            <td>${{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>

                                            <td>
                                                @if ($detalle->comensal)
                                                    Persona {{ $detalle->comensal->numero }}
                                                @else
                                                    <span class="badge bg-secondary">General</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($detalle->estado == 'entregado')
                                                    <span class="badge bg-success">Entregado</span>
                                                @elseif ($detalle->estado == 'pagado')
                                                    <span class="badge bg-dark">Pagado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Total seleccionado --}}
                            <div class="mt-3">
                                <label class="form-label fw-bold">Total a cobrar:</label>
                                <input type="text"
                                       id="total_cobrar"
                                       name="total_final"
                                       class="form-control"
                                       readonly>
                            </div>

                            {{-- PROPINAS --}}
                            <div class="mt-4">
                                <label class="form-label fw-bold">Propina:</label>

                                <div class="input-group mb-2" style="max-width: 300px;">
                                    <span class="input-group-text">$</span>
                                    <input type="number"
                                           step="0.01"
                                           id="propina_input"
                                           name="propina"
                                           class="form-control"
                                           value="0">
                                </div>

                                <div class="btn-group mb-3">
                                    <button type="button" class="btn btn-outline-secondary propina-btn" data-value="0.05">5%</button>
                                    <button type="button" class="btn btn-outline-secondary propina-btn" data-value="0.10">10%</button>
                                    <button type="button" class="btn btn-outline-secondary propina-btn" data-value="0.15">15%</button>
                                    <button type="button" class="btn btn-outline-secondary propina-btn" data-value="0.20">20%</button>
                                </div>

                                <p class="fw-bold">
                                    Total con propina:
                                    <span id="total_con_propina">$0.00</span>
                                </p>

                                <p class="fw-bold">
                                    Faltante:
                                    <span id="faltante" class="text-danger">$0.00</span>
                                </p>
                            </div>

                            <hr>

                            <div class="mt-3">
                                <label class="fw-bold">Método de pago:</label>
                                <select id="metodo_pago_select" class="form-select">
                                    <option>Selecciona el método de pago</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="mixto">Mixto</option>
                                </select>
                            </div>

                            <div class="mt-3 d-none" id="pago_simple_global">
                                <label class="fw-bold">Monto recibido:</label>
                                <input type="number"
                                    step="0.01"
                                    class="form-control"
                                    id="pago_simple_input"
                                    placeholder="$0.00">

                                <div class="mt-2 d-none" id="cambio_global_row">
                                    <strong>Cambio:</strong>
                                    <span id="cambio_global" class="fw-bold text-success">$0.00</span>
                                </div>
                            </div>


                            {{-- PAGOS GLOBALES --}}
                            <div class="mt-3" id="pagos_container_wrapper">
                                <label class="fw-bold">Pagos:</label>
                                <button type="button" class="btn btn-sm btn-outline-primary mb-2 d-none" id="add_pago">
                                    + Añadir pago
                                </button>
                                <div id="pagos_container" class="d-none"></div>
                            </div>
                        </div>

                        {{-- BOTÓN CONFIRMAR --}}
                        <div class="mt-4" id="btn_confirmar_wrapper">
                            <button id="btn_confirmar" class="btn btn-success w-100">
                                <i class="fas fa-check-circle"></i> Confirmar Cobro
                            </button>
                        </div>


                    </div> {{-- card-body --}}
                </div> {{-- card --}}
            </div> {{-- col --}}
        </div> {{-- row --}}
    </form>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   const TODO_PAGADO = {{ $todoPagado ? 'true' : 'false' }};
</script>

<script>
/* ============================================================
   CONSTANTES Y ELEMENTOS PRINCIPALES
============================================================ */
const tipoCobroSelect      = document.getElementById('tipo_cobro');
const tipoCobroInput       = document.getElementById('tipo_cobro_input');
const seccionCompartidos   = document.getElementById('seccion_compartidos');
const seccionPorComensal   = document.getElementById('seccion_por_comensal');
const seccionCobroCompleto = document.getElementById('seccion_cobro_completo');

const divisionesInput      = document.getElementById('divisiones_compartidos');
const cobrosInput          = document.getElementById('cobros_por_comensal');
const pagosGlobalesInput   = document.getElementById('pagos_globales');

const filasDetalle         = document.querySelectorAll('.fila-detalle');
const totalInput           = document.getElementById('total_cobrar');

const propinaInputGlobal   = document.getElementById('propina_input');
const totalConPropinaEl    = document.getElementById('total_con_propina');
const faltanteEl           = document.getElementById('faltante');
const pagosContainer       = document.getElementById('pagos_container');
const btnConfirmar         = document.getElementById('btn_confirmar');
const metodoPagoSelect     = document.getElementById('metodo_pago_select');

const totalPedido          = parseFloat({{ $pedido->total }});

/* COMENSALES para JS */
const COMENSALES = @json(
    $pedido->comensales->map(fn($c) => [
        'id'     => $c->id,
        'numero' => $c->numero,
    ])
);

// Mantener sincronizado el select con el hidden SIEMPRE
tipoCobroSelect.addEventListener("change", function () {
    tipoCobroInput.value = this.value;
});

/* ============================================================
   MODO DE COBRO (COMPLETO / SEPARADO)
============================================================ */
function actualizarModoCobro() {
    const modo = tipoCobroSelect.value;
    tipoCobroInput.value = modo;

    if (modo === 'completo') {
        // Mostrar secciones del cobro completo
        seccionCobroCompleto.classList.remove('d-none');
        seccionCompartidos.classList.add('d-none');
        seccionPorComensal.classList.add('d-none');

        // Mostrar botón global
        document.getElementById("btn_confirmar_wrapper").classList.remove("d-none");

        actualizarTotalConPropina();
    } 
    else {
        // Ocultar secciones del cobro completo
        seccionCobroCompleto.classList.add('d-none');

        // Mostrar secciones por comensal
        seccionCompartidos.classList.remove('d-none');
        seccionPorComensal.classList.remove('d-none');

        // OCULTAR BOTÓN GLOBAL
        document.getElementById("btn_confirmar_wrapper").classList.add("d-none");

        cargarProductosPorComensal();
    }
}

// Detectar cuando cambia de "Cuenta completa" a "Cuenta por comensal"
tipoCobroSelect.addEventListener('change', actualizarModoCobro);


/* ============================================================
   SECCIÓN CUENTA COMPLETA — TOTAL + PROPINAS + PAGOS
============================================================ */

let PAGOS_GLOBALES = [];

function calcularTotalSeleccionado() {
    let total = 0;
    document.querySelectorAll('.detalle-check:checked').forEach(c => {
        total += parseFloat(c.dataset.precio);
    });
    totalInput.value = total.toFixed(2);
    actualizarTotalConPropina();
}

function activarMetodoPagoGlobal() {
    if (!metodoPagoSelect) return;

    const pagoSimpleDiv   = document.getElementById('pago_simple_global');
    const cambioRowGlobal = document.getElementById('cambio_global_row');
    const addPagoBtn      = document.getElementById('add_pago');
    const pagosWrapper    = document.getElementById('pagos_container_wrapper');

    metodoPagoSelect.onchange = null;

    metodoPagoSelect.addEventListener('change', function () {
        const metodo = this.value;

        // reset pagos globales
        PAGOS_GLOBALES = [];

        if (metodo === 'mixto') {
            // modo mixto → many pagos
            pagoSimpleDiv.classList.add('d-none');
            if (cambioRowGlobal) cambioRowGlobal.classList.add('d-none');

            if (pagosWrapper) pagosWrapper.classList.remove('d-none');
            if (addPagoBtn) addPagoBtn.classList.remove('d-none');
        } else {
            // modo simple
            pagoSimpleDiv.classList.remove('d-none');
            if (pagosWrapper) pagosWrapper.classList.add('d-none');
            if (addPagoBtn) addPagoBtn.classList.add('d-none');

            // mostrar/ocultar cambio según efectivo
            if (metodo === 'efectivo') {
                if (cambioRowGlobal) cambioRowGlobal.classList.remove('d-none');
            } else {
                if (cambioRowGlobal) cambioRowGlobal.classList.add('d-none');
                const spanCambio = document.getElementById('cambio_global');
                if (spanCambio) spanCambio.innerText = '$0.00';
            }

            // pago simple inicial
            PAGOS_GLOBALES = [{
                id: 'simple',
                monto: 0,
                metodo: metodo,
                referencia: ''
            }];
        }

        renderPagosGlobales();     // deja la estructura interna coherente
        actualizarTotalConPropina();
    });
}


function renderPagosGlobales() {
    pagosContainer.innerHTML = '';

    PAGOS_GLOBALES.forEach(p => {
        const row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2', 'align-items-center');

        row.innerHTML = `
            <input type="number" step="0.01"
                   class="form-control pago-monto-global"
                   data-id="${p.id}"
                   value="${p.monto}">
            <select class="form-select pago-metodo-global"
                    data-id="${p.id}"
                    style="max-width:150px;">
                <option value="efectivo" ${p.metodo === 'efectivo' ? 'selected' : ''}>Efectivo</option>
                <option value="tarjeta" ${p.metodo === 'tarjeta' ? 'selected' : ''}>Tarjeta</option>
                <option value="transferencia" ${p.metodo === 'transferencia' ? 'selected' : ''}>Transferencia</option>
            </select>
            <input type="text"
                   class="form-control pago-ref-global"
                   data-id="${p.id}"
                   style="max-width:200px;"
                   placeholder="Referencia"
                   value="${p.referencia ?? ''}">
            <button type="button"
                    class="btn btn-danger btn-sm pago-del-global"
                    data-id="${p.id}">
                X
            </button>
        `;

        pagosContainer.appendChild(row);
    });

    // Listeners
    document.querySelectorAll('.pago-monto-global').forEach(inp => {
        inp.addEventListener('input', e => {
            const id = e.target.dataset.id;
            const pago = PAGOS_GLOBALES.find(x => x.id == id);
            pago.monto = parseFloat(e.target.value || 0);
            actualizarTotalConPropina();
        });
    });

    document.querySelectorAll('.pago-metodo-global').forEach(sel => {
        sel.addEventListener('change', e => {
            const id = e.target.dataset.id;
            const pago = PAGOS_GLOBALES.find(x => x.id == id);
            pago.metodo = e.target.value;
            actualizarTotalConPropina();
        });
    });

    document.querySelectorAll('.pago-ref-global').forEach(inp => {
        inp.addEventListener('input', e => {
            const id = e.target.dataset.id;
            const pago = PAGOS_GLOBALES.find(x => x.id == id);
            pago.referencia = e.target.value;
            actualizarTotalConPropina();
        });
    });

    document.querySelectorAll('.pago-del-global').forEach(btn => {
        btn.addEventListener('click', e => {
            const id = e.target.dataset.id;
            PAGOS_GLOBALES = PAGOS_GLOBALES.filter(p => p.id != id);
            renderPagosGlobales();
            actualizarTotalConPropina();
        });
    });
}

function actualizarTotalConPropina() {

        // 🔥 SI EL PEDIDO YA ESTÁ PAGADO, TODO ES 0
    if (TODO_PAGADO) {
        totalInput.value = "0.00";
        totalConPropinaEl.innerText = "$0.00";
        faltanteEl.innerText = "$0.00";
        btnConfirmar.disabled = true;
        return;
    }
    
    const seleccionado = parseFloat(totalInput.value || 0);
    const base = (seleccionado > 0 ? seleccionado : totalPedido);
    const propina = parseFloat(propinaInputGlobal.value || 0);

    const total = base + propina;
    totalConPropinaEl.innerText = `$${total.toFixed(2)}`;

    const sumaPagos = PAGOS_GLOBALES.reduce((acc, p) => acc + (p.monto || 0), 0);
    const faltante = total - sumaPagos;

    faltanteEl.innerText = `$${faltante.toFixed(2)}`;
    faltanteEl.classList.toggle('text-danger', faltante > 0.01);
    faltanteEl.classList.toggle('text-success', faltante <= 0.01);

    pagosGlobalesInput.value = JSON.stringify(PAGOS_GLOBALES);

    if (tipoCobroSelect.value === 'completo') {
        btnConfirmar.disabled = faltante > 0.01;
    }
}

// Checkboxes detalle
document.querySelectorAll('.detalle-check').forEach(chk => {
    chk.addEventListener('change', calcularTotalSeleccionado);
});

// Botones de propina %
document.querySelectorAll('.propina-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const pct = parseFloat(btn.dataset.value);
        const seleccionado = parseFloat(totalInput.value || 0);
        const base = (seleccionado > 0 ? seleccionado : totalPedido);
        const prop = base * pct;
        propinaInputGlobal.value = prop.toFixed(2);
        actualizarTotalConPropina();
    });
});

// Propina manual
propinaInputGlobal.addEventListener('input', actualizarTotalConPropina);

// Agregar pago global
document.getElementById('add_pago').addEventListener('click', () => {
    const id = Date.now();
    PAGOS_GLOBALES.push({
        id,
        monto: 0,
        metodo: 'efectivo',
        referencia: ''
    });
    renderPagosGlobales();
    actualizarTotalConPropina();
});

/* ============================================================
   SECCIÓN 1 — PLATILLOS COMPARTIDOS
============================================================ */
let divisiones = {};

function actualizarInputDivisiones() {
    divisionesInput.value = JSON.stringify(divisiones);
    // refrescar productos por comensal si estamos en modo separado
    if (tipoCobroSelect.value === 'separado') {
        cargarProductosPorComensal();
    }
}

document.querySelectorAll('.metodo-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        const id = this.dataset.id;
        const card = document.querySelector(`.producto-compartido[data-id="${id}"]`);
        const total = parseFloat(card.dataset.total);
        const resultadoDiv = document.getElementById(`resultado_${id}`);
        const btnAsignar = card.querySelector('.btn-asignar-final');

        let metodo = this.value;
        let html = '';

        if (!divisiones[id]) {
            divisiones[id] = { metodo: metodo, total: total, partes: {} };
        } else {
            divisiones[id].metodo = metodo;
            divisiones[id].total = total;
            divisiones[id].partes = {};
        }

        // 50 / 50
        if (metodo === '50_50') {
            let mitad = (total / 2).toFixed(2);
            html += `<h6 class="fw-bold">División 50/50</h6>`;
            let primeros = COMENSALES.slice(0, 2);
            primeros.forEach(c => {
                html += `Persona ${c.numero} → <strong>$${mitad}</strong><br>`;
                divisiones[id].partes[c.id] = parseFloat(mitad);
            });
        }

        // Entre todos
        else if (metodo === 'todos') {
            let parte = (total / COMENSALES.length).toFixed(2);
            html += `<h6 class="fw-bold">Entre todos</h6>`;
            COMENSALES.forEach(c => {
                html += `Persona ${c.numero} → <strong>$${parte}</strong><br>`;
                divisiones[id].partes[c.id] = parseFloat(parte);
            });
        }

        // Por monto
        else if (metodo === 'monto') {
            html += `
                <h6 class="fw-bold">Dividir por monto manual</h6>
                <p>Total: $${total.toFixed(2)}</p>
            `;
            COMENSALES.forEach(c => {
                html += `
                    <div class="mb-2">
                        Persona ${c.numero}:
                        <input type="number"
                               class="form-control monto-manual"
                               data-id="${id}"
                               data-persona="${c.id}"
                               placeholder="Monto">
                    </div>
                `;
            });
        }

        // 100% a alguien
        else if (metodo === '100') {
            html += `<h6 class="fw-bold">Asignar 100% a un comensal</h6>`;
            COMENSALES.forEach(c => {
                html += `
                    <div class="form-check">
                        <input type="radio"
                               class="form-check-input radio-100"
                               name="full_${id}"
                               data-id="${id}"
                               value="${c.id}">
                        <label>Persona ${c.numero}</label>
                    </div>
                `;
            });
        }

        // Por porcentajes
        else if (metodo === 'porcentaje') {
            html += `
                <h6 class="fw-bold">Dividir por porcentajes</h6>
                <p>Total: $${total.toFixed(2)}</p>
                <small class="text-muted">Los porcentajes deben sumar 100%</small><hr>
            `;
            COMENSALES.forEach(c => {
                html += `
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2">Persona ${c.numero}</span>
                        <input type="number"
                               class="form-control w-25 porcentaje-input"
                               min="0" max="100"
                               data-id="${id}"
                               data-total="${total}"
                               data-persona="${c.id}">
                        <span class="ms-2" id="monto_${id}_${c.id}">$0.00</span>
                    </div>
                `;
            });
        }

        resultadoDiv.innerHTML = html;
        resultadoDiv.classList.remove('d-none');
        btnAsignar.classList.remove('d-none');

        actualizarInputDivisiones();
    });
});

// Por monto (input)
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('monto-manual')) {
        const id = e.target.dataset.id;
        const persona = e.target.dataset.persona;
        const monto = parseFloat(e.target.value || 0);

        if (!divisiones[id]) return;
        divisiones[id].partes[persona] = monto;
        actualizarInputDivisiones();
    }
});

// 100% a alguien
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('radio-100')) {
        const id = e.target.dataset.id;
        const persona = e.target.value;

        if (!divisiones[id]) return;

        divisiones[id].partes = {};
        divisiones[id].partes[persona] = divisiones[id].total;

        actualizarInputDivisiones();
    }
});

// Por porcentajes
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('porcentaje-input')) {
        const id = e.target.dataset.id;
        const total = parseFloat(e.target.dataset.total);
        const persona = e.target.dataset.persona;

        let porcentaje = parseFloat(e.target.value || 0);
        if (porcentaje < 0) porcentaje = 0;
        if (porcentaje > 100) porcentaje = 100;

        let monto = (total * (porcentaje / 100)).toFixed(2);

        const spanMonto = document.getElementById(`monto_${id}_${persona}`);
        if (spanMonto) spanMonto.innerText = `$${monto}`;

        if (!divisiones[id]) return;
        divisiones[id].partes[persona] = parseFloat(monto);
        actualizarInputDivisiones();
    }
});

document.addEventListener('input', function (e) {
    if (e.target.id === 'pago_simple_input') {

        let monto = parseFloat(e.target.value || 0);
        const metodo = metodoPagoSelect ? metodoPagoSelect.value : 'efectivo';

        // mismo cálculo que actualizarTotalConPropina
        const seleccionado = parseFloat(totalInput.value || 0);
        const base = (seleccionado > 0 ? seleccionado : totalPedido);
        const propina = parseFloat(propinaInputGlobal.value || 0);
        const total = base + propina;

        // TARJETA / TRANSFERENCIA → no permitir más del total
        if (metodo === 'tarjeta' || metodo === 'transferencia') {
            if (monto > total) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Monto inválido',
                    text: 'Con tarjeta o transferencia no puedes cobrar más del total.',
                });
                monto = total;
                e.target.value = total.toFixed(2);
            }

            const cambioSpan = document.getElementById('cambio_global');
            if (cambioSpan) cambioSpan.innerText = '$0.00';
        }

        // EFECTIVO → permitir más y calcular cambio
        if (metodo === 'efectivo') {
            let cambio = monto - total;
            if (cambio < 0) cambio = 0;
            const cambioSpan = document.getElementById('cambio_global');
            if (cambioSpan) cambioSpan.innerText = `$${cambio.toFixed(2)}`;
        }

        // guardar pago simple en PAGOS_GLOBALES
        PAGOS_GLOBALES = [{
            id: 'simple',
            monto: monto,
            metodo: metodo,
            referencia: ''
        }];

        renderPagosGlobales();   // mantiene el JSON de pagos
        actualizarTotalConPropina();
    }
});


// Botón asignar a comensales (solo confirma)
document.querySelectorAll('.btn-asignar-final').forEach(btn => {
    btn.addEventListener('click', () => {
        Swal.fire({
            icon: 'success',
            title: 'Asignación guardada',
            text: 'La división del platillo se registró correctamente.',
            timer: 1500,
            showConfirmButton: false
        });
    });
});

/* ============================================================
   SECCIÓN 2 — COBRO POR COMENSAL (COBROS JSON)
============================================================ */
let COBROS = {};

function cargarProductosPorComensal() {
    // Inicializar estructura
    document.querySelectorAll('.card-comensal').forEach(card => {
        const comId = card.dataset.id;

        COBROS[comId] = {
            productos: [],
            subtotal: 0,
            propina: 0,
            total: 0,
            metodo_pago: 'efectivo',
            pagos: [],
            paga_por: comId
        };

        const cont = document.getElementById(`productos_comensal_${comId}`);
        cont.innerHTML = `<h6 class="fw-bold text-primary">Productos:</h6>`;

        // Productos individuales (no compartidos)
        document.querySelectorAll(`tr[data-comensal="${comId}"]`).forEach(row => {
            if (row.querySelector('.badge.bg-dark')) {
            return; // 🔥 este detalle ya está pagado
        }



            const nombre = row.children[1].innerText;
            const total = parseFloat(row.dataset.totalLinea || 0);

            cont.innerHTML += `
                <div><i class="fas fa-check text-success"></i> ${nombre} — $${total.toFixed(2)}</div>
            `;

            COBROS[comId].productos.push({
                detalle_id: row.dataset.detalleId,
                nombre,
                total
            });

            COBROS[comId].subtotal += total;
        });
    });

    // Productos compartidos desde divisiones
    if (divisionesInput.value) {
        let divs;
        try {
            divs = JSON.parse(divisionesInput.value);
        } catch (e) {
            divs = {};
        }

        Object.keys(divs).forEach(detId => {
            const partes = divs[detId].partes || {};
            Object.keys(partes).forEach(comId => {
                const monto = parseFloat(partes[comId] || 0);
                const cont = document.getElementById(`productos_comensal_${comId}`);
                if (!cont) return;

                cont.innerHTML += `
                    <div class="text-muted">
                        <i class="fas fa-share-alt"></i> Parte de platillo compartido — $${monto.toFixed(2)}
                    </div>
                `;

                COBROS[comId].subtotal += monto;
            });
        });
    }

    recalcularTotalesComensal();
    activarMetodoPagoComensal();

}

function recalcularTotalesComensal() {
    Object.keys(COBROS).forEach(comId => {
        const c = COBROS[comId];

        c.total = c.subtotal + c.propina;

        const subEl = document.getElementById(`subtotal_${comId}`);
        const totEl = document.getElementById(`total_${comId}`);
        const totConPropEl = document.getElementById(`total_con_propina_${comId}`);
        const faltanteEl = document.getElementById(`faltante_com_${comId}`);

        const sumaPagos = c.pagos.reduce((acc, p) => acc + (p.monto || 0), 0);
        const faltante = c.total - sumaPagos;

        if (subEl) subEl.innerText = `$${c.subtotal.toFixed(2)}`;
        if (totEl) totEl.innerText = `$${c.total.toFixed(2)}`;
        if (totConPropEl) totConPropEl.innerText = `$${c.total.toFixed(2)}`;

        if (faltanteEl) {
            faltanteEl.innerText = `$${faltante.toFixed(2)}`;
            faltanteEl.classList.toggle('text-danger', faltante > 0.01);
            faltanteEl.classList.toggle('text-success', faltante <= 0.01);
        }
    });

    cobrosInput.value = JSON.stringify(COBROS);
}


// Propina por comensal
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('propina-com')) {
        const comId = e.target.dataset.id;
        const val = parseFloat(e.target.value || 0);
        if (!COBROS[comId]) return;
        COBROS[comId].propina = val;
        recalcularTotalesComensal();
    }
});

// Botones de propina % por comensal
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('propina-btn-com')) {
        const comId = e.target.dataset.id;
        const pct = parseFloat(e.target.dataset.value);

        COBROS[comId].propina = COBROS[comId].subtotal * pct;

        // actualizar input
        document.querySelector(`.propina-com[data-id="${comId}"]`).value =
            COBROS[comId].propina.toFixed(2);

        recalcularTotalesComensal();
    }
});


/* ============================================================
   MÉTODO DE PAGO POR COMENSAL — VERSIÓN DEFINITIVA
============================================================ */
function activarMetodoPagoComensal() {

    document.querySelectorAll(".metodo-com").forEach(select => {

        select.onchange = null;

        select.addEventListener("change", function () {

            const comId  = this.dataset.id;
            const metodo = this.value;

            COBROS[comId].metodo_pago = metodo;

            const pagosDivMixto = document.getElementById("pagos_com_" + comId);
            const pagoSimpleDiv = document.getElementById("pago_simple_" + comId);
            const cambioRow     = document.getElementById("cambio_row_" + comId);

            // Reset pagos
            COBROS[comId].pagos = [];

            if (metodo === "mixto") {
                pagosDivMixto.classList.remove("d-none");
                pagoSimpleDiv.classList.add("d-none");
                if (cambioRow) cambioRow.classList.add("d-none");
            } else {
                pagoSimpleDiv.classList.remove("d-none");
                pagosDivMixto.classList.add("d-none");

                // pago simple
                COBROS[comId].pagos = [{
                    monto: 0,
                    metodo: metodo,
                    referencia: null
                }];

                if (metodo === 'efectivo') {
                    if (cambioRow) cambioRow.classList.remove('d-none');
                } else {
                    if (cambioRow) cambioRow.classList.add('d-none');
                    const spanCambio = document.getElementById(`cambio_${comId}`);
                    if (spanCambio) spanCambio.innerText = '$0.00';
                }
            }

            recalcularTotalesComensal();
        });
    });
}



// Añadir pago mixto por comensal
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('add_pago_com')) {
        const comId = e.target.dataset.id;
        const cont = document.getElementById(`pagos_list_${comId}`);
        const pagoId = Date.now();

        cont.innerHTML += `
            <div class="d-flex gap-2 mb-2 pago-item" data-id="${pagoId}">
                <input type="number" step="0.01" class="form-control pago-monto-com" placeholder="Monto">
                <select class="form-select pago-metodo-com">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
                <input type="text" class="form-control pago-ref-com" placeholder="Referencia">
                <button type="button" class="btn btn-danger btn-sm del-pago-com">X</button>
            </div>
        `;
    }
});

// Editar pagos mixtos
document.addEventListener('input', function (e) {
    if (
        e.target.classList.contains('pago-monto-com') ||
        e.target.classList.contains('pago-metodo-com') ||
        e.target.classList.contains('pago-ref-com')
    ) {
        const item = e.target.closest('.pago-item');
        const card = e.target.closest('.card-comensal');
        const comId = card.dataset.id;

        if (!COBROS[comId]) return;

        COBROS[comId].pagos = [];

        document.querySelectorAll(`#pagos_list_${comId} .pago-item`).forEach(p => {
            COBROS[comId].pagos.push({
                monto: parseFloat(p.querySelector('.pago-monto-com').value || 0),
                metodo: p.querySelector('.pago-metodo-com').value,
                referencia: p.querySelector('.pago-ref-com').value || null
            });
        });

        recalcularTotalesComensal();
    }
});

// Eliminar pago mixto
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('del-pago-com')) {
        const item = e.target.closest('.pago-item');
        item.remove();

        const card = e.target.closest('.card-comensal');
        const comId = card.dataset.id;

        if (!COBROS[comId]) return;

        COBROS[comId].pagos = [];
        document.querySelectorAll(`#pagos_list_${comId} .pago-item`).forEach(p => {
            COBROS[comId].pagos.push({
                monto: parseFloat(p.querySelector('.pago-monto-com').value || 0),
                metodo: p.querySelector('.pago-metodo-com').value,
                referencia: p.querySelector('.pago-ref-com').value || null
            });
        });

        recalcularTotalesComensal();
    }
});

document.addEventListener('input', function (e) {
    if (e.target.classList.contains('pago-simple-input')) {
        const comId  = e.target.dataset.id;
        let monto    = parseFloat(e.target.value || 0);

        if (!COBROS[comId]) return;

        const metodo = COBROS[comId].metodo_pago;
        const total  = COBROS[comId].total;

        // ---- TARJETA / TRANSFERENCIA: NO PERMITIR MÁS DEL TOTAL ----
        if (metodo === 'tarjeta' || metodo === 'transferencia') {

            if (monto > total) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Monto inválido',
                    text: 'Con tarjeta o transferencia no puedes cobrar más del total.',
                });

                monto = total;
                e.target.value = total.toFixed(2);
            }

            // En tarjeta / transferencia no manejamos cambio
            document.getElementById(`cambio_${comId}`).innerText = `$0.00`;
        }

        // ---- EFECTIVO: PERMITE MÁS DEL TOTAL Y CALCULA CAMBIO ----
        if (metodo === 'efectivo') {
            let cambio = monto - total;
            if (cambio < 0) cambio = 0;
            document.getElementById(`cambio_${comId}`).innerText = `$${cambio.toFixed(2)}`;
        }

        // Guardar pago simple en COBROS
        COBROS[comId].pagos = [{
            monto: monto,
            metodo: metodo,
            referencia: null
        }];

        recalcularTotalesComensal();
    }
});
;


// Paga por
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('paga-por')) {
        const comId = e.target.dataset.id;
        if (!COBROS[comId]) return;
        COBROS[comId].paga_por = e.target.value;
        recalcularTotalesComensal();
    }
});

/* ============================================================
   ESTADO INICIAL
============================================================ */
(function init() {
    // Por defecto: cuenta completa
    // seleccionar todos los detalles y calcular total
    document.querySelectorAll('.detalle-check').forEach(c => c.checked = true);
    calcularTotalSeleccionado();
    renderPagosGlobales();
    actualizarTotalConPropina();
    actualizarModoCobro(); // aplica modo inicial (completo)
    activarMetodoPagoGlobal(); 
})();

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-cobrar-comensal')) {

        const comId = e.target.dataset.id;

        if (!COBROS[comId]) {
            console.error('No hay datos de COBROS para comensal', comId);
            return;
        }

        // Si el método es mixto, valido que la suma de pagos coincida con el total
        if (COBROS[comId].metodo_pago === 'mixto') {
            const suma = COBROS[comId].pagos.reduce((acc, p) => acc + (p.monto || 0), 0);
            if (Math.abs(suma - COBROS[comId].total) > 0.01) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pagos incompletos',
                    text: `Los pagos de Persona ${comId} no coinciden con su total.`,
                });
                return;
            }
        }

        // 📦 SOLO mando este comensal en el JSON
        const envio = {};
        envio[comId] = COBROS[comId];

        cobrosInput.value     = JSON.stringify(envio);
        divisionesInput.value = JSON.stringify(divisiones);
        tipoCobroInput.value  = "separado";

        console.log("👉 Enviando cobro SOLO de comensal", comId, envio);

        // 🔴 SIN Swal, envío directo
        document.getElementById('form_cobro').submit();
    }
});




window.onload = function() {

    console.log("DOM cargado → script de compartidos activado");

    const seccionCompartidos = document.getElementById("seccion_compartidos");
    const btnOcultar = document.getElementById("btn_ocultar_compartidos");

    if (!seccionCompartidos || !btnOcultar) {
        console.log('No encontré seccion_compartidos o btn_ocultar_compartidos');
        return;
    }

    btnOcultar.addEventListener("click", () => {
        seccionCompartidos.classList.add("d-none");

        if (!document.getElementById("btn_mostrar_compartidos")) {
            const btnMostrar = document.createElement("button");
            btnMostrar.id = "btn_mostrar_compartidos";
            btnMostrar.className = "btn btn-primary my-3";
            btnMostrar.innerHTML = "Mostrar platillos compartidos";

            btnMostrar.onclick = () => {
                seccionCompartidos.classList.remove("d-none");
                btnMostrar.remove();
            };

            seccionCompartidos.parentElement.insertBefore(
                btnMostrar,
                seccionCompartidos
            );
        }
    });

};


document.getElementById('form_cobro').addEventListener('submit', function() {
    console.log("📤 EL FORMULARIO SE ESTÁ ENVIANDO (submit detectado)");
});



</script>
@endpush

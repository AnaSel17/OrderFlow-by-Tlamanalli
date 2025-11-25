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

    <form action="{{ route('pedidos.finalizarCobro', $pedido) }}" method="POST">
        @csrf
        @method('PATCH')

        <input type="hidden" name="tipo_cobro" id="tipo_cobro_input" value="completo">

        <div class="row">

            {{-- ===================================================== --}}
            {{-- COLUMNA IZQUIERDA --}}
            {{-- ===================================================== --}}
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-body">

                        {{-- Tipo de cobro --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de cobro:</label>
                            <select id="tipo_cobro" class="form-select">
                                <option value="completo">Cuenta completa</option>
                                <option value="separado">Cuenta por comensal</option>
                            </select>
                        </div>

                        {{-- Selector de comensal --}}
                        <div class="mb-3 d-none" id="selector_comensal">
                            <label class="form-label fw-bold">Selecciona comensal:</label>
                            <select id="comensal_filter" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach($pedido->comensales as $c)
                                    <option value="{{ $c->id }}">Persona {{ $c->numero }}</option>
                                @endforeach
                            </select>
                        </div>

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
                                                <button 
                                                    type="button" 
                                                    class="btn btn-sm btn-outline-primary asignar-btn mt-1"
                                                    data-id="{{ $detalle->id }}"
                                                    data-nombre="{{ $detalle->producto->nombre }}"
                                                    data-precio="{{ $detalle->precio_unitario * $detalle->cantidad }}"
                                                >
                                                    <i class="fas fa-user-friends"></i> Asignar
                                                </button>
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
                            <input type="text" id="total_cobrar" name="total_cobrado" class="form-control" readonly>
                        </div>
{{-- ================================ --}}
{{-- PROPINAS --}}
{{-- ================================ --}}
<div class="mt-4">
    <label class="form-label fw-bold">Propina:</label>

    <div class="input-group mb-2" style="max-width: 300px;">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" id="propina_input" name="propina" class="form-control" value="0">
    </div>

    {{-- Botones de propina sugerida --}}
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

{{-- ================================ --}}
{{-- MÉTODO DE PAGO --}}
{{-- ================================ --}}
<div class="mt-3">
    <label class="form-label fw-bold">Método de pago:</label>
    <select class="form-select" id="metodo_pago_select" name="metodo_pago" style="max-width:300px;">
        <option value="mixto" selected>Mixto (varios métodos)</option>
        <option value="efectivo">Solo efectivo</option>
        <option value="tarjeta">Solo tarjeta</option>
        <option value="transferencia">Solo transferencia</option>
    </select>
</div>

{{-- ================================ --}}
{{-- PAGOS GLOBALES --}}
{{-- ================================ --}}
<div class="mt-3 d-flex justify-content-between align-items-center">
    <strong>Pagos globales:</strong>

    <button type="button" id="add_pago" class="btn btn-sm btn-outline-primary">
        + Agregar pago
    </button>
</div>

<div id="pagos_container" class="mt-2"></div>

<p class="text-muted mt-2">
    La suma de los pagos debe coincidir con el total a cobrar.
</p>

{{-- ================================ --}}
{{-- BOTÓN CONFIRMAR --}}
{{-- ================================ --}}
<div class="mt-4">
    <button id="btn_confirmar" class="btn btn-success w-100" disabled>
        <i class="fas fa-check-circle"></i> Confirmar Cobro
    </button>
</div>

                    </div> {{-- cierre de card-body --}}
                </div> {{-- cierre de card --}}
            </div> {{-- cierre de columna izquierda --}}


            @push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* =========================================
   VARIABLES ORIGINALES
========================================= */
const tipoCobroSelect     = document.getElementById('tipo_cobro');
const tipoCobroInput      = document.getElementById('tipo_cobro_input');
const selectorComensalDiv = document.getElementById('selector_comensal');
const comensalFilter      = document.getElementById('comensal_filter');
const totalInput          = document.getElementById('total_cobrar');
const filas               = document.querySelectorAll('.fila-detalle');

/* =========================================
   MAPA DE COMENSALES
========================================= */
const COMENSALES = @json(
    $pedido->comensales->map(fn($c) => [
        'id'     => $c->id,
        'numero' => $c->numero,
    ])
);

const COMENSALES_MAP = {};
COMENSALES.forEach(c => COMENSALES_MAP[c.id] = c);

/* =========================================
   RESUMEN POR COMENSAL
========================================= */
function recalcularResumenPorComensal() {

    const resumen = {};

    COMENSALES.forEach(c => {
        resumen[c.id] = { label: `Persona ${c.numero}`, total: 0 };
    });

    resumen['general'] = { label: 'General', total: 0 };

    filas.forEach(row => {
        const comensalId  = row.dataset.comensal || 'general';
        const totalLinea  = parseFloat(row.dataset.totalLinea);

        if (!isNaN(totalLinea)) {
            if (!resumen[comensalId]) {
                resumen[comensalId] = {
                    label: COMENSALES_MAP[comensalId]
                          ? `Persona ${COMENSALES_MAP[comensalId].numero}`
                          : 'General',
                    total: 0
                };
            }
            resumen[comensalId].total += totalLinea;
        }
    });

    const contenedor = document.getElementById('resumen-comensales');
    contenedor.innerHTML = '';

    let sumaPorComensal = 0;

    Object.keys(resumen).forEach(key => {
        const item = resumen[key];
        if (item.total <= 0) return;

        if (key !== 'general') sumaPorComensal += item.total;

        const div = document.createElement('div');
        div.classList.add('mb-2', 'p-2', 'border', 'rounded');
        div.innerHTML = `
            <div class="d-flex justify-content-between">
                <span><i class="fas fa-user"></i> ${item.label}</span>
                <strong>$${item.total.toFixed(2)}</strong>
            </div>
        `;
        contenedor.appendChild(div);
    });

    document.getElementById('suma-por-comensal').innerText = `$${sumaPorComensal.toFixed(2)}`;

    const alerta = document.getElementById('alerta-cuadre');
    const totalPedido = {{ $pedido->total }};

    if (Math.abs(totalPedido - sumaPorComensal) < 0.01) {
        alerta.innerHTML = `<div class="alert alert-success">✔ Coincide perfectamente</div>`;
    } else {
        alerta.innerHTML = `<div class="alert alert-warning">⚠ Falta cuadrar la cuenta</div>`;
    }
}

/* =========================================
   FILTROS + TOTAL SELECCIONADO
========================================= */
function filtrarPorComensal(id) {
    filas.forEach(row => {
        const chk = row.querySelector('.detalle-check');
        const cid = row.dataset.comensal;

        if (cid == id || cid === "general") {
            row.style.display = '';
            if (chk) chk.disabled = false;
        } else {
            row.style.display = 'none';
            if (chk) chk.checked = false;
        }
    });
}

function mostrarTodos() {
    filas.forEach(row => {
        row.style.display = '';
        const chk = row.querySelector('.detalle-check');
        if (chk) chk.disabled = false;
    });
}

function seleccionarTodos() {
    document.querySelectorAll('.detalle-check').forEach(c => c.checked = true);
}

function calcularTotalSeleccionado() {
    let total = 0;
    document.querySelectorAll('.detalle-check:checked').forEach(c => {
        total += parseFloat(c.dataset.precio);
    });
    totalInput.value = total.toFixed(2);

    actualizarTotalConPropina();
}

/* =========================================
   PROPINA + PAGOS (VERSIÓN FINAL)
========================================= */

let totalBase = parseFloat({{ $pedido->total }});
let propina = 0;
let pagos = [];

const propinaInput      = document.getElementById("propina_input");
const totalConPropinaEl = document.getElementById("total_con_propina");
const faltanteEl        = document.getElementById("faltante");
const pagosContainer    = document.getElementById("pagos_container");
const btnConfirmar      = document.getElementById("btn_confirmar");

function actualizarTotalConPropina() {
    const seleccionado = parseFloat(totalInput.value || 0);
    const base = seleccionado > 0 ? seleccionado : totalBase;

    const total = base + propina;
    totalConPropinaEl.innerText = `$${total.toFixed(2)}`;

    const sumaPagos = pagos.reduce((acc, p) => acc + p.monto, 0);
    const faltante = total - sumaPagos;

    faltanteEl.innerText = `$${faltante.toFixed(2)}`;
    faltanteEl.classList.toggle("text-danger", faltante > 0.001);
    faltanteEl.classList.toggle("text-success", faltante <= 0.001);

    btnConfirmar.disabled = faltante > 0.001;
}

/*----------- PROPINA % -----------*/
document.querySelectorAll('.propina-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const pct = parseFloat(btn.dataset.value);
        const seleccionado = parseFloat(totalInput.value || 0);
        const base = seleccionado > 0 ? seleccionado : totalBase;

        propina = base * pct;
        propinaInput.value = propina.toFixed(2);
        actualizarTotalConPropina();
    });
});

/*----------- PROPINA MANUAL ----------*/
propinaInput.addEventListener('input', () => {
    propina = parseFloat(propinaInput.value || 0);
    actualizarTotalConPropina();
});

/*----------- AGREGAR PAGO -----------*/
document.getElementById("add_pago").addEventListener("click", () => {
    const id = Date.now();

    pagos.push({
        id,
        monto: 0,
        metodo: "efectivo",
        referencia: ""
    });

    renderPagos();
    actualizarTotalConPropina();
});

/*----------- MOSTRAR PAGOS -----------*/
function renderPagos() {
    pagosContainer.innerHTML = "";

    pagos.forEach(p => {
        const row = document.createElement("div");
        row.classList.add("d-flex", "gap-2", "mb-2");

        row.innerHTML = `
            <input type="number" step="0.01" class="form-control pago-monto" data-id="${p.id}" value="${p.monto}">
            
            <select class="form-select pago-metodo" data-id="${p.id}" style="max-width:150px;">
                <option value="efectivo" ${p.metodo === 'efectivo' ? 'selected' : ''}>Efectivo</option>
                <option value="tarjeta" ${p.metodo === 'tarjeta' ? 'selected' : ''}>Tarjeta</option>
                <option value="transferencia" ${p.metodo === 'transferencia' ? 'selected' : ''}>Transferencia</option>
            </select>

            <input type="text" placeholder="Referencia" class="form-control pago-ref" 
                   data-id="${p.id}" style="max-width:200px;" value="${p.referencia}">

            <button type="button" class="btn btn-danger btn-sm pago-del" data-id="${p.id}">X</button>
        `;

        pagosContainer.appendChild(row);
    });

    /* Eventos */
    document.querySelectorAll(".pago-monto").forEach(inp => {
        inp.addEventListener("input", e => {
            const pago = pagos.find(x => x.id == e.target.dataset.id);
            pago.monto = parseFloat(e.target.value || 0);
            actualizarTotalConPropina();
        });
    });

    document.querySelectorAll(".pago-metodo").forEach(sel => {
        sel.addEventListener("change", e => {
            const pago = pagos.find(x => x.id == e.target.dataset.id);
            pago.metodo = e.target.value;
        });
    });

    document.querySelectorAll(".pago-ref").forEach(inp => {
        inp.addEventListener("input", e => {
            const pago = pagos.find(x => x.id == e.target.dataset.id);
            pago.referencia = e.target.value;
        });
    });

    document.querySelectorAll(".pago-del").forEach(btn => {
        btn.addEventListener("click", e => {
            pagos = pagos.filter(p => p.id != e.target.dataset.id);
            renderPagos();
            actualizarTotalConPropina();
        });
    });
}

/* =========================================
   ASIGNACIÓN – ORIGINAL
========================================= */
let asignacionesGenerales = {};
let precioProducto = 0;
let productoActual = null;

document.querySelectorAll('.asignar-btn').forEach(btn => {
    btn.addEventListener('click', async function () {

        productoActual = this.dataset.id;
        precioProducto  = parseFloat(this.dataset.precio);

        let htmlComensales = `<div style='text-align:left'>`;

        @foreach($pedido->comensales as $c)
            htmlComensales += `
                <div class="form-check">
                    <input type="checkbox" value="{{ $c->id }}" class="form-check-input swal-com">
                    <label class="form-check-label">Persona {{ $c->numero }}</label>
                </div>
            `;
        @endforeach

        htmlComensales += `</div>`;

        let result = await Swal.fire({
            title: 'Asignar producto general',
            html: `
                <h5>${this.dataset.nombre}</h5>
                <p><strong>Total:</strong> $${precioProducto}</p>
                ${htmlComensales}
                <hr>
                <p id="division">Selecciona personas...</p>
            `,
            showCancelButton: true,
            confirmButtonText: "Asignar",
            didOpen: () => {
                document.querySelectorAll('.swal-com').forEach(chk => {
                    chk.addEventListener('change', () => {
                        let seleccionados = document.querySelectorAll('.swal-com:checked');
                        if (seleccionados.length === 0) {
                            document.getElementById('division').innerText = "Selecciona personas...";
                        } else {
                            let monto = precioProducto / seleccionados.length;
                            document.getElementById('division').innerText =
                                `${seleccionados.length} personas → $${monto.toFixed(2)} c/u`;
                        }
                    });
                });
            },
            preConfirm: () => {
                let seleccionados = [];
                document.querySelectorAll('.swal-com:checked').forEach(c => seleccionados.push(c.value));
                if (seleccionados.length === 0) {
                    Swal.showValidationMessage("Selecciona al menos 1 comensal");
                    return false;
                }
                return seleccionados;
            }
        });

        if (result.isConfirmed) {
            asignacionesGenerales[productoActual] = result.value;

            Swal.fire({
                icon: "success",
                title: "Asignación guardada",
                text: "Producto asignado correctamente",
                timer: 1500,
                showConfirmButton: false
            });

            recalcularResumenPorComensal();
        }
    });
});

/* =========================================
   ESTADO INICIAL
========================================= */
mostrarTodos();
seleccionarTodos();
calcularTotalSeleccionado();
recalcularResumenPorComensal();
renderPagos();
actualizarTotalConPropina();
</script>
@endpush

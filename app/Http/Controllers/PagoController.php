<?php

// app/Http/Controllers/PagoController.php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cuenta;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{

       public function index(Request $request)
    {
        // =============================
        // FILTROS
        // =============================
        $metodo     = $request->metodo;
        $desde      = $request->desde;
        $hasta      = $request->hasta;

        $pagosQuery = Pago::with(['cuenta.pedido', 'cuenta.comensal', 'recibidoPor']);

        if ($metodo) {
            $pagosQuery->where('metodo', $metodo);
        }

        if ($desde) {
            $pagosQuery->whereDate('created_at', '>=', $desde);
        }

        if ($hasta) {
            $pagosQuery->whereDate('created_at', '<=', $hasta);
        }

        $pagos = $pagosQuery->orderBy('created_at', 'desc')->paginate(20);


        // =============================
        // CÁLCULOS GLOBALES
        // =============================

        // Cuentas pagadas
        $cuentas = Cuenta::with('pagos')
            ->where('estado', 'pagada')
            ->get();

        // 🔥 Total sin propinas
        $totalCobrado = $cuentas->sum('subtotal');

        // 🔥 Total propinas
        $totalPropinas = $cuentas->sum('propina');

        // 🔥 Total general (subtotal + propina)
        $totalGeneral = $cuentas->sum('total');

        // 🔥 Total por método (efectivo, tarjeta, transferencia)
        $totalPorMetodo = Pago::select('metodo', DB::raw('SUM(monto) as total'))
            ->groupBy('metodo')
            ->get();

        // 🔥 Total de cambio entregado
        $cambioTotal = 0;

        foreach ($cuentas as $cuenta) {
            foreach ($cuenta->pagos as $pago) {
                if ($pago->metodo === 'efectivo') {
                    $cambio = $pago->monto - $cuenta->total;
                    if ($cambio > 0) {
                        $cambioTotal += $cambio;
                    }
                }
            }
        }

        return view('pagos.index', compact(
            'pagos',
            'totalCobrado',
            'totalPropinas',
            'totalGeneral',
            'totalPorMetodo',
            'cambioTotal'
        ));
    }
    /**
     * Muestra el formulario para registrar pagos.
     */
    public function mostrarFormularioPago(Pedido $pedido, Cuenta $cuenta)
    {
        // Traer la suma total de pagos ya registrados para esta cuenta
        $totalPagado = Pago::where('cuenta_id', $cuenta->id)->sum('monto');
        $pendientePago = $cuenta->total - $totalPagado;

        if ($pendientePago <= 0 && $cuenta->estado === 'pagada') {
            return redirect()->route('cobro.opciones', $pedido)->with('info', 'La cuenta ya está saldada.');
        }

        return view('billing.payment_form', compact('pedido', 'cuenta', 'totalPagado', 'pendientePago'));
    }

    /**
     * Registra un nuevo pago.
     * Una cuenta se cierra cuando total_pagado >= total.
     * El pedido se cierra cuando TODAS las cuentas están pagadas.
     */
    public function registrarPago(Request $request, Pedido $pedido, Cuenta $cuenta)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'metodo' => 'required|string|max:30',
            'propina_monto' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $cuenta, $pedido) {
            $montoPago = $request->monto;
            $propinaAdicional = $request->propina_monto ?? 0;

            $totalPagadoActual = Pago::where('cuenta_id', $cuenta->id)->sum('monto');
            
            // 1. Manejo de Propina (opcional y editable)
            if ($propinaAdicional > 0) {
                $cuenta->propina += $propinaAdicional;
                $cuenta->total += $propinaAdicional;
                $cuenta->save();
            }

            // 2. Registrar Pago
            Pago::create([
                'cuenta_id' => $cuenta->id,
                'metodo' => $request->metodo,
                'monto' => $montoPago,
                'referencia' => $request->referencia,
                'recibido_por' => Auth::id(),
            ]);

            // 3. Verificar estado de la cuenta
            $totalPagadoFinal = Pago::where('cuenta_id', $cuenta->id)->sum('monto');
            $cambio = max(0, $totalPagadoFinal - $cuenta->total);

            if ($totalPagadoFinal >= $cuenta->total) {
                $cuenta->estado = 'pagada';
            } else {
                $cuenta->estado = 'parcial';
            }
            $cuenta->save();
            
            // 4. Verificar estado del Pedido
            $cuentasPendientes = $pedido->cuentas()->where('estado', '!=', 'pagada')->count();

            if ($cuentasPendientes === 0) {
                $pedido->update(['estado' => 'pagado', 'cerrada_en' => now()]);
            }

            $mensaje = 'Pago de ' . number_format($montoPago, 2) . ' registrado. ';
            if ($cuenta->estado === 'pagada') {
                $mensaje .= 'Cuenta CERRADA. ¡Cambio a entregar: ' . number_format($cambio, 2) . '!';
            } else {
                $mensaje .= 'Pendiente de pago: ' . number_format($cuenta->total - $totalPagadoFinal, 2);
            }

            return redirect()->route('cobro.opciones', $pedido)->with('success', $mensaje);
        });
    }
}
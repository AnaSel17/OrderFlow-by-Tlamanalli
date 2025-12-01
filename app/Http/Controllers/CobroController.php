<?php

// app/Http/Controllers/CobroController.php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Pago;
use App\Models\Cuenta;
use App\Models\CuentaDetalle;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CobroController extends Controller
{

public function finalizarCobro(Request $request, Pedido $pedido)
{
    // ❌ Evitar cobrar pedidos ya pagados
    if ($pedido->estado === 'pagado') {
        return back()->with('error', 'Este pedido ya está totalmente pagado. No se pueden registrar más pagos.');
    }

    // 🔍 Para que VEAS que llega la info
    Log::info('FINALIZAR_COBRO_REQUEST', [
        'tipo_cobro'            => $request->tipo_cobro,
        'cobros_por_comensal'   => $request->cobros_por_comensal,
        'divisiones_compartidos'=> $request->divisiones_compartidos,
        'pagos_globales'        => $request->pagos_globales,
    ]);

    DB::beginTransaction();

    try {

        if ($request->tipo_cobro === 'separado') {
            // 💡 Maneja SIEMPRE el cobro por comensal aquí
            $this->cobrarCuentaPorComensal($pedido, $request);
        } else {
            // 💡 Y este es el flujo de cuenta completa
            $this->cobrarCuentaCompleta($pedido, $request);
        }

        DB::commit();
        return redirect()->route('pedidos.show', $pedido->id)
    ->with('success', 'Pago registrado correctamente.');


    } catch (\Throwable $th) {
        DB::rollBack();
        Log::error('ERROR_FINALIZAR_COBRO', [
            'error' => $th->getMessage(),
            'trace' => $th->getTraceAsString(),
        ]);
        return back()->with('error', $th->getMessage());
    }
}



private function cobrarCuentaCompleta(Pedido $pedido, Request $request)
{
    $pagos = json_decode($request->pagos_globales, true) ?? [];

    // crear cuenta
    $cuenta = Cuenta::create([
        'pedido_id'  => $pedido->id,
        'tipo'       => 'completa',
        'comensal_id'=> null,
        'usuario_id' => Auth::id(),
        'subtotal'   => $request->total_final,
        'descuento'  => 0,
        'propina'    => $request->propina,
        'total'      => $request->total_final + $request->propina,
        'estado'     => 'pagada',
    ]);

    // guardar detalles
    foreach ($pedido->detalles as $d) {
        if ($d->estado === 'pagado') continue;

        CuentaDetalle::create([
            'cuenta_id'        => $cuenta->id,
            'detalle_id'       => $d->id,
            'comensal_id'      => null,
            'cantidad_asignada'=> $d->cantidad,
            'precio_unitario'  => $d->precio_unitario,
            'descuento'        => $d->descuento,
            'subtotal_asignado'=> $d->cantidad * $d->precio_unitario,
            'asignado_completo' => true, // default

        ]);

        $d->update(['estado' => 'pagado']);
    }

    // guardar pagos
    foreach ($pagos as $p) {
        Pago::create([
            'cuenta_id'   => $cuenta->id,
            'metodo'      => $p['metodo'],
            'monto'       => $p['monto'],
            'referencia'  => $p['referencia'] ?? null,
            'recibido_por'=> Auth::id(),
        ]);
    }

    $pedido->update([
    'estado'     => 'pagado',
    'cerrada_en' => now(),
]);

}

private function cobrarCuentaPorComensal(Pedido $pedido, Request $request)
{
    $cobros = json_decode($request->cobros_por_comensal, true) ?? [];
    $divisiones = json_decode($request->divisiones_compartidos, true) ?? [];

    foreach ($cobros as $comId => $data) {

        // crear cuenta del comensal
        $cuenta = Cuenta::create([
            'pedido_id'  => $pedido->id,
            'comensal_id'=> $comId,
            'usuario_id' => Auth::id(),
            'tipo'       => 'comensal',
            'subtotal'   => $data['subtotal'],
            'descuento'  => 0,
            'propina'    => $data['propina'],
            'total'      => $data['total'],
            'estado'     => 'pagada',
        ]);

        // guardar productos individuales asignados
        foreach ($pedido->detalles as $d) {

            if ($d->comensal_id != $comId) continue;
            if ($d->estado === 'pagado') continue;

            CuentaDetalle::create([
                'cuenta_id'        => $cuenta->id,
                'detalle_id'       => $d->id,
                'comensal_id'      => $comId,
                'cantidad_asignada'=> $d->cantidad,
                'precio_unitario'  => $d->precio_unitario,
                'descuento'        => $d->descuento,
                'subtotal_asignado'=> $d->cantidad * $d->precio_unitario,
                'asignado_completo'=> true,
            ]);

            $d->update(['estado' => 'pagado']);
        }


        // guardar partes de productos compartidos
        foreach ($divisiones as $detId => $info) {

            if (!isset($info['partes'][$comId])) continue;

            $monto = $info['partes'][$comId];

            $detalle = DetallePedido::find($detId);

            // 1️⃣ Guardar el fragmento asignado
            CuentaDetalle::create([
                'cuenta_id'        => $cuenta->id,
                'detalle_id'       => $detalle->id,
                'comensal_id'      => $comId,
                'cantidad_asignada'=> 1,
                'precio_unitario'  => $monto,
                'descuento'        => 0,
                'subtotal_asignado'=> $monto,
                'asignado_completo'=> false,
            ]);

            // 2️⃣ Calcular cuánto se ha pagado hasta ahora por ese detalle
            $sumaPagada = CuentaDetalle::where('detalle_id', $detId)->sum('subtotal_asignado');

            // 3️⃣ Total original del detalle
            $totalOriginal = $detalle->cantidad * $detalle->precio_unitario;

            // 4️⃣ Si ya está cubierto al 100%, marcarlo como pagado
            if ($sumaPagada >= $totalOriginal - 0.01) {
                $detalle->update(['estado' => 'pagado']);
            }
        }


        // guardar pagos del comensal
        foreach ($data['pagos'] as $p) {
            Pago::create([
                'cuenta_id'   => $cuenta->id,
                'metodo'      => $p['metodo'],
                'monto'       => $p['monto'],
                'referencia'  => $p['referencia'] ?? null,
                'recibido_por'=> Auth::id(),
            ]);
        }
    }
    $detallesPendientes = $pedido->detalles()->where('estado', '!=', 'pagado')->exists();

if (!$detallesPendientes) {
    $pedido->update([
        'estado'     => 'pagado',
        'cerrada_en' => now(),
    ]);
}

}

}
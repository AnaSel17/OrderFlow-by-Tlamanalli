<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pedido_id'   => 'required|exists:pedidos,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad'    => 'required|integer|min:1',
            'comensal_id' => 'nullable|exists:comensales,id',
            'notas'       => 'nullable|string|max:200',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        DetallePedido::create([
            'pedido_id'      => $request->pedido_id,
            'producto_id'    => $request->producto_id,
            'cantidad'       => $request->cantidad,
            'precio_unitario'=> $producto->precio,
            'notas'          => $request->notas,
            'comensal_id'    => $request->comensal_id,
            'estado'    => 'pendiente',
        ]);

        // Actualizar total del pedido
        $pedido = Pedido::find($request->pedido_id);
        $pedido->total += $producto->precio * $request->cantidad;
        $pedido->save();

        return back()->with('success', 'Producto agregado al pedido correctamente.');
    }

    public function update(Request $request, DetallePedido $detallePedido)
    {
        $detallePedido->update($request->only(['producto_id', 'cantidad', 'notas']));
        return back()->with('success', 'Detalle actualizado correctamente.');
    }

    public function marcarEnPreparacion(DetallePedido $detalle)
    {
        $detalle->update(['estado' => 'en_preparacion']);

        $this->recalcularEstadoPedido($detalle->pedido);

        return back()->with('success', 'Platillo en preparación');
    }

    public function marcarListo(DetallePedido $detalle)
    {
        $detalle->update(['estado' => 'listo']);

        $this->recalcularEstadoPedido($detalle->pedido);

        return back()->with('success', 'Platillo listo');
    }

    public function marcarEntregado(DetallePedido $detalle)
    {
        $detalle->update(['estado' => 'entregado']);

        $this->recalcularEstadoPedido($detalle->pedido);

        return back()->with('success', 'Platillo entregado correctamente.');
    }

    /**
     * 🔥 SOLO ESTA FUNCIÓN para recalcular el estado del PEDIDO.
     */
private function recalcularEstadoPedido(Pedido $pedido)
{
    $detalles = $pedido->detalles; // relación hasMany

    $total       = $detalles->count();
    $pend = $detalles->where('estado', 'pendiente')->count();
    $enviados    = $detalles->where('estado', 'enviado_cocina')->count();
    $preparando  = $detalles->where('estado', 'en_preparacion')->count();
    $listos      = $detalles->where('estado', 'listo')->count();
    $entregados  = $detalles->where('estado', 'entregado')->count();

    //  Si no hay productos aún, NO cambiar estado
    if ($total === 0) {
        $pedido->update(['estado' => 'pendiente']);
        return;
    }

    // 🔵 Hay pendientes o enviados: sigue en cocina
    if ($pend > 0 || $enviados > 0) {
        $pedido->update(['estado' => 'enviado_cocina']);
        return;
    }

    // 🟠 Hay algo en preparación: el pedido está en preparación
    if ($preparando > 0) {
        $pedido->update(['estado' => 'en_preparacion']);
        return;
    }

    // 🟢 Todos listos (cocina terminó)
    if ($listos === $total) {
        $pedido->update(['estado' => 'listo']);
        return;
    }

    // 💵 Todo entregado → listo para cobrar
    if ($entregados === $total) {
        $pedido->update(['estado' => 'listo_para_cobrar']);
        return;
    }

    // Mezcla de listos + entregados → sigue preparación
    //$pedido->update(['estado' => 'en_preparacion']);

   


}


public function prepararGrupo(Request $request)
{
    $ids = explode(',', $request->ids);

    // Obtener detalles
    $detalles = DetallePedido::whereIn('id', $ids)->get();

    if ($detalles->isEmpty()) {
        return back()->with('error', 'No se encontraron detalles del pedido.');
    }

    // Obtener pedido
    $pedido = $detalles->first()->pedido;

    // Cambiar SOLO los que estén en estado enviado_cocina
    foreach ($detalles as $detalle) {
        if ($detalle->estado === 'enviado_cocina') {
            $detalle->update(['estado' => 'en_preparacion']);
        }
    }

    // 🔥 Recalcular estado del pedido
    $this->recalcularEstadoPedido($pedido);

    return back()->with('info', 'Platillos puestos en preparación.');
}


public function listoGrupo(Request $request)
{
    $ids = explode(',', $request->ids);

    // Obtener detalles
    $detalles = DetallePedido::whereIn('id', $ids)->get();

    if ($detalles->isEmpty()) {
        return back()->with('error', 'No se encontraron detalles del pedido.');
    }

    // Obtener pedido
    $pedido = $detalles->first()->pedido;

    // Cambiar SOLO los que estén en preparación
    foreach ($detalles as $detalle) {
        if ($detalle->estado === 'en_preparacion') {
            $detalle->update(['estado' => 'listo']);
        }
    }

    // 🔥 Recalcular estado del pedido
    $this->recalcularEstadoPedido($pedido);

    return back()->with('success', 'Platillos marcados como listos.');
}

public function destroy(DetallePedido $detallePedido)
{
    $pedido = $detallePedido->pedido; // obtener el pedido antes de borrar

    // Restar el total correspondiente
    $pedido->total -= ($detallePedido->precio_unitario * $detallePedido->cantidad);
    if ($pedido->total < 0) $pedido->total = 0; // seguridad por si acaso
    $pedido->save();

    // Eliminar el detalle
    $detallePedido->delete();

    // Recalcular estado del pedido
    $this->recalcularEstadoPedido($pedido);

    return back()->with('success', 'Producto eliminado del pedido.');
}

public function cancelar(DetallePedido $detallePedido)
{
    $pedido = $detallePedido->pedido; // obtener el pedido

    // Si ya estaba cancelado, no hacer nada
    if ($detallePedido->estado === 'cancelado') {
        return back()->with('info', 'Este producto ya estaba cancelado.');
    }

    // Restar del total del pedido
    $pedido->total -= ($detallePedido->precio_unitario * $detallePedido->cantidad);
    if ($pedido->total < 0) $pedido->total = 0;
    $pedido->save();

    // Marcar el detalle como cancelado
    $detallePedido->update([
        'estado' => 'cancelado'
    ]);

    // Recalcular estado del pedido
    $this->recalcularEstadoPedido($pedido);

    return back()->with('success', 'Producto cancelado del pedido.');
}




}

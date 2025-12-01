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
        $this->recalcularEstadoComanda($detalle->comanda);


        return back()->with('success', 'Platillo en preparación');
    }

    public function marcarListo(DetallePedido $detalle)
    {
        $detalle->update(['estado' => 'listo']);

        $this->recalcularEstadoPedido($detalle->pedido);
        $this->recalcularEstadoComanda($detalle->comanda);


        return back()->with('success', 'Platillo listo');
    }

    public function marcarEntregado(DetallePedido $detalle)
    {
        $detalle->update(['estado' => 'entregado']);

        $this->recalcularEstadoPedido($detalle->pedido);
        $this->recalcularEstadoComanda($detalle->comanda);


        return back()->with('success', 'Platillo entregado correctamente.');
    }

    /**
     * 🔥 SOLO ESTA FUNCIÓN para recalcular el estado del PEDIDO.
     */
private function recalcularEstadoPedido(Pedido $pedido)
{
    $detalles = $pedido->detalles; 

    // EXCLUIR CANCELADOS PARA TODA LA LÓGICA
    $activos = $detalles->where('estado', '!=', 'cancelado');

    $total       = $activos->count();
    $pend        = $activos->where('estado', 'pendiente')->count();
    $enviados    = $activos->where('estado', 'enviado_cocina')->count();
    $preparando  = $activos->where('estado', 'en_preparacion')->count();
    $listos      = $activos->where('estado', 'listo')->count();
    $entregados  = $activos->where('estado', 'entregado')->count();

    if ($total === 0) {
        $pedido->update(['estado' => 'pendiente']);
        return;
    }

    if ($pend > 0 || $enviados > 0) {
        $pedido->update(['estado' => 'enviado_cocina']);
        return;
    }

    if ($preparando > 0) {
        $pedido->update(['estado' => 'en_preparacion']);
        return;
    }

    if ($listos === $total) {
        $pedido->update(['estado' => 'listo']);
        return;
    }

    if ($entregados === $total) {
        $pedido->update(['estado' => 'listo_para_cobrar']);
        return;
    }
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
    $this->recalcularEstadoComanda($detalle->comanda);


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
    $this->recalcularEstadoComanda($detalle->comanda);


    return back()->with('success', 'Platillos marcados como listos.');
}

public function destroy(DetallePedido $detallePedido)
{
    $pedido = $detallePedido->pedido; // obtener el pedido antes de borrar
     $comanda = $detallePedido->comanda; // ESTA ES LA CLAVE

    // Restar el total correspondiente
    $pedido->total -= ($detallePedido->precio_unitario * $detallePedido->cantidad);
    if ($pedido->total < 0) $pedido->total = 0; // seguridad por si acaso
    $pedido->save();

    // Eliminar el detalle
    $detallePedido->delete();

    // Recalcular estado del pedido
    $this->recalcularEstadoPedido($pedido);
    $this->recalcularEstadoComanda($comanda);


    return back()->with('success', 'Producto eliminado del pedido.');
}

public function cancelar(DetallePedido $detallePedido)
{
    $pedido = $detallePedido->pedido; // obtener el pedido
     $comanda = $detallePedido->comanda; // ESTA ES LA CLAVE

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
    $this->recalcularEstadoComanda($comanda);


    return back()->with('success', 'Producto cancelado del pedido.');
}

public function entregarSeleccionados(Request $request)
{
    $ids = $request->input('detalles_entregar', []);

    if (empty($ids)) {
        return back()->with('error', 'Selecciona al menos un platillo para entregar.');
    }
 // Obtener los detalles
    $detalles = DetallePedido::whereIn('id', $ids)
                ->where('estado', 'listo')
                ->get();

    if ($detalles->isEmpty()) {
        return back()->with('error', 'Los platillos seleccionados no están listos para entregar.');
    }

    // ✔ Actualizar a entregado uno por uno
    foreach ($detalles as $detalle) {
        $detalle->update(['estado' => 'entregado']);
    }

    // ✔ Obtener el pedido y recalcular
    $pedido = $detalles->first()->pedido;
    $this->recalcularEstadoPedido($pedido);

    return back()->with('success', 'Platillos entregados correctamente.');
}

private function recalcularEstadoComanda($comanda)
{
    if (!$comanda) return;

    // Detalles SOLO de esa comanda
    $detalles = $comanda->pedido->detalles->where('comanda_id', $comanda->id);

    // Cancelados no cuentan
    $activos = $detalles->where('estado', '!=', 'cancelado');

    if ($activos->count() === 0) {
        // Si todos están cancelados → dejarla lista por seguridad
        $comanda->update(['estado' => 'listo']);
        return;
    }

    $total          = $activos->count();
    $pendientes     = $activos->where('estado', 'pendiente')->count();
    $enviados       = $activos->where('estado', 'enviado_cocina')->count();
    $preparando     = $activos->where('estado', 'en_preparacion')->count();
    $listos         = $activos->where('estado', 'listo')->count();
    $entregados     = $activos->where('estado', 'entregado')->count();

    // 1️⃣ Algo en preparación → comanda en preparación
    if ($preparando > 0) {
        $comanda->update(['estado' => 'en_preparacion']);
        return;
    }

    // 2️⃣ Hay cosas pendientes o enviadas → enviada a cocina
    if ($pendientes > 0 || $enviados > 0) {
        $comanda->update(['estado' => 'enviado_cocina']);
        return;
    }

    // 3️⃣ Todos entregados → comanda entregada (ESTADO FINAL)
    if ($entregados === $total) {
        $comanda->update(['estado' => 'entregada']);
        return;
    }

    // 4️⃣ Todos listos → lista para entregar al mesero
    if ($listos === $total) {
        $comanda->update(['estado' => 'listo']);
        return;
    }

    // fallback
    $comanda->update(['estado' => 'enviado_cocina']);
}


}




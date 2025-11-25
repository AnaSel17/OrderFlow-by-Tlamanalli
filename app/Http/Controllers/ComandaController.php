<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\DetallePedido;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComandaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $comandas = Comanda::with('pedido.mesas', 'pedido.detalles.producto', 'pedido.usuario')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('comandas.index', compact('comandas'));
    }

    public function updateDetalleEstado(Request $request, $id)
    {
        $detalle = DetallePedido::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:pendiente,en_preparacion,listo,entregado'
        ]);

        $detalle->update(['estado' => $request->estado]);

        // Si todos los detalles del pedido están listos → marcar pedido como listo
        $pedido = $detalle->pedido;
        if ($pedido->detalles()->whereNotIn('estado', ['listo', 'entregado'])->count() === 0) {
            $pedido->update(['estado' => 'listo']);
        }

        return back()->with('success', 'Estado del platillo actualizado correctamente.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
        DB::beginTransaction();

        $pedido = Pedido::with(['detalles.producto', 'mesas'])->findOrFail($request->pedido_id);

        // 1️⃣ Buscar productos aún pendientes
        $pendientes = $pedido->detalles()->where('estado', 'pendiente')->get();

        if ($pendientes->isEmpty()) {
            throw new \Exception('⚠️ No hay nuevos platillos pendientes para enviar a cocina.');
        }

        // 2️⃣ Calcular número de ronda
        $ronda = Comanda::where('pedido_id', $pedido->id)->count() + 1;

        // 3️⃣ Crear comanda
        $comanda = Comanda::create([
            'pedido_id'  => $pedido->id,
            'estado'     => 'enviado_cocina',
            'enviada_en' => now(),
        ]);

        // 4️⃣ Actualizar detalles asociados
        foreach ($pendientes as $detalle) {
            $detalle->update([
                'estado'     => 'enviado_cocina',
                'comanda_id' => $comanda->id,
            ]);
        }

        // 5️⃣ Cambiar estado del pedido si corresponde
        if (in_array($pedido->estado, ['pendiente', 'entregado'])) {
            $pedido->update(['estado' => 'enviado_cocina']);
        }

        DB::commit();

        $mesas = $pedido->mesas_texto ?? '—';
        return back()->with('success', "📤 Comanda #{$comanda->id} — Ronda {$ronda} enviada correctamente a cocina (Mesas: {$mesas}).");

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', '❌ No se pudo enviar la comanda: ' . $e->getMessage());
    }

    }
    

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comanda $comanda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comanda $comanda)
    {
         $request->validate(['estado' => 'required|in:enviado_cocina,en_preparacion,listo']);

    $comanda->update(['estado' => $request->estado]);

    // Actualizar también el estado de los platillos
    foreach ($comanda->detalles as $detalle) {
        $detalle->update(['estado' => $request->estado]);
    }

    // Si todos los detalles del pedido están listos, actualizar el pedido
    $pedido = $comanda->pedido;
    if ($pedido->detalles()->whereNotIn('estado', ['listo', 'entregado'])->count() === 0) {
        $pedido->update(['estado' => 'listo']);
    }

    return back()->with('success', 'Estado de la comanda actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comanda $comanda)
    {
        //
    }
}

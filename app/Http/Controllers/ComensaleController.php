<?php

namespace App\Http\Controllers;

use App\Models\Comensale;
use App\Models\Pedido;
use Illuminate\Http\Request;

class ComensaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'cantidad' => 'required|integer|min:1|max:20',
        ]);

        $pedido = Pedido::findOrFail($request->pedido_id);

        for ($i = 1; $i <= $request->cantidad; $i++) {
            Comensale::create([
                'pedido_id' => $pedido->id,
                'numero' => $i,
            ]);
        }

        return back()->with('success', 'Comensales agregados correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comensale $comensale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comensale $comensale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comensale $comensale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comensale $comensale)
    {
        $comensale->delete();
        return back()->with('success', 'Comensal eliminado correctamente.');
    
    }
}

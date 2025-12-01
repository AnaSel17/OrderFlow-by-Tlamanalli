<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * 
     */

    public function abiertas()
    {
        $cuentas = Cuenta::with(['pedido', 'comensal'])
                        ->whereIn('estado', ['abierta', 'parcial'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('cuentas.abiertas', compact('cuentas'));
    }

    public function pagadas()
    {
        $cuentas = Cuenta::with(['pedido', 'comensal', 'pagos'])
                        ->where('estado', 'pagada')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('cuentas.pagadas', compact('cuentas'));
    }

    public function tickets()
{
    // Tickets = cuentas pagadas
    $tickets = Cuenta::with(['pedido', 'usuario', 'comensal', 'pagos'])
        ->where('estado', 'pagada')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('tickets.index', compact('tickets'));
}

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cuenta $cuenta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cuenta $cuenta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cuenta $cuenta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cuenta $cuenta)
    {
        //
    }
}

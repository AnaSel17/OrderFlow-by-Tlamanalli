<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Http\Requests\InventarioRequest;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventarios = Inventario::with('producto')->paginate(10);
        return view('inventarios.index', compact('inventarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::all();
        return view('inventarios.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventarioRequest $request)
    {
        $estado = $this->definirEstado($request->stock_actual, $request->punto_reorden);

        Inventario::create([
            'producto_id'   => $request->producto_id,
            'stock_actual'  => $request->stock_actual,
            'punto_reorden' => $request->punto_reorden,
            'estado'        => $estado,
        ]);

        return redirect()->route('inventarios.index')->with('success', 'Inventario registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventario $inventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventario $inventario)
    {
        $productos = Producto::all();
        return view('inventarios.edit', compact('inventario', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventarioRequest $request, Inventario $inventario)
    {
         $estado = $this->definirEstado($request->stock_actual, $request->punto_reorden);

        $inventario->update([
            'stock_actual'  => $request->stock_actual,
            'punto_reorden' => $request->punto_reorden,
            'estado'        => $estado,
        ]);

        return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventario $inventario)
    {
         $inventario->delete();
        return redirect()->route('inventarios.index')->with('success', 'Registro eliminado correctamente.');
    }

    private function definirEstado($stock, $reorden)
    {
        return $stock <= 0
            ? 'Agotado'
            : ($stock <= $reorden ? 'Bajo' : 'Suficiente');
    }
}

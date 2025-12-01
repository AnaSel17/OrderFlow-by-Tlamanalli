<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'sku' => 'required|string|max:60|unique:productos',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'activo' => 'boolean',
        ]);

        Producto::create($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
{
    $request->validate([
        'nombre'       => 'required|string|max:150',
        'sku'          => 'required|string|max:60|unique:productos,sku,' . $producto->id,
        'precio'       => 'required|numeric|min:0',
        'categoria_id' => 'required|exists:categorias,id',
        'activo'       => 'nullable|boolean',
    ]);

    $producto->update([
        'nombre'       => $request->nombre,
        'sku'          => $request->sku,
        'precio'       => $request->precio,
        'categoria_id' => $request->categoria_id,
        'activo'       => $request->boolean('activo'), // 👈 AQUÍ LA MAGIA
    ]);

    return redirect()
        ->route('productos.index')
        ->with('success', 'Producto actualizado correctamente.');
}


    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}

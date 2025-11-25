<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
     public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(CategoriaRequest $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias',
            'descripcion' => 'nullable|string',
        ]);

        Categoria::create($request->validated());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
        ]);

        $categoria->update($request->validated());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        // Verificar si tiene productos asociados
    if ($categoria->productos()->count() > 0) {
        return redirect()->route('categorias.index')
            ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
    }
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    
}

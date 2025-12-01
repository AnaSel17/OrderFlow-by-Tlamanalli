<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZonaRequest;
use App\Models\Zona;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ZonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $zonas = Zona::orderBy('nombre')->paginate(10);
        return view('zonas.index', compact('zonas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // Días por defecto
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        return view('zonas.create', compact('diasSemana'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ZonaRequest  $request)
    {
        $data = $request->validated();

        // Si no seleccionaron días, usar todos los días por defecto
        $data['dias_activos'] = $request->dias_activos ?? ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // Si no marcaron "activa", dejarla true por defecto
        $data['activa'] = $request->boolean('activa', true);

        // Convertir a formato 24 h (H:i)
        if (!empty($data['hora_apertura'])) {
            $data['hora_apertura'] = Carbon::parse($data['hora_apertura'])->format('H:i');
        }
        if (!empty($data['hora_cierre'])) {
            $data['hora_cierre'] = Carbon::parse($data['hora_cierre'])->format('H:i');
        }


        // Crear zona
        Zona::create($data);

        return redirect()
            ->route('zonas.index')
            ->with('success', 'Zona registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Zona $zona)
    {
         return view('zonas.show', compact('zona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zona $zona)
    {
        $diasSemana = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
        return view('zonas.edit', compact('zona', 'diasSemana'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ZonaRequest $request, Zona $zona)
    {
        $data = $request->validated();

        $data['dias_activos'] = $request->dias_activos ?? ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
        $data['activa'] = $request->boolean('activa', true);

        // Convertir a formato 24 h (H:i)
        if (!empty($data['hora_apertura'])) {
            $data['hora_apertura'] = Carbon::parse($data['hora_apertura'])->format('H:i');
        }
        if (!empty($data['hora_cierre'])) {
            $data['hora_cierre'] = Carbon::parse($data['hora_cierre'])->format('H:i');
        }

        $zona->update($data);

        return redirect()
            ->route('zonas.index')
            ->with('success', 'Zona actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zona $zona)
    {
        // Verificar si la zona tiene mesas asociadas
    $tieneMesas = DB::table('mesas')->where('zona_id', $zona->id)->exists();

    if ($tieneMesas) {
        return redirect()
            ->route('zonas.index')
            ->with('error', 'No se puede eliminar la zona porque tiene mesas asignadas. Debes reasignarlas o eliminarlas primero.');
    }

    // Si no tiene mesas, proceder con la eliminación
    $zona->delete();

    return redirect()
        ->route('zonas.index')
        ->with('success', 'Zona eliminada correctamente.');
    }
}

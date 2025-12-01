<?php

namespace App\Http\Controllers;

use App\Http\Requests\MesaRequest;
use App\Jobs\RecordatorioPedido;
use App\Models\Comensale;
use App\Models\Mesa;
use App\Models\MesaPedido;
use App\Models\Zona;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mesas = Mesa::with('zona')->paginate(10);
        return view('mesas.index', compact('mesas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo zonas activas y abiertas
        $zonas = Zona::all()->filter(fn($z) => $z->estaAbierta());
        return view('mesas.create', compact('zonas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MesaRequest $request)
    {
        Mesa::create($request->validated());
        return redirect()->route('mesas.index')->with('success', 'Mesa creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mesa $mesa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mesa $mesa)
    {
        $zonas = Zona::all();
        return view('mesas.edit', compact('mesa', 'zonas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MesaRequest $request, Mesa $mesa)
    {
        // 🔸 No permitir editar mesas ocupadas con pedidos activos
        $tienePedidosActivos = $mesa->pedidos()
            ->where('estado', '!=', 'cerrado')
            ->exists();

        if ($tienePedidosActivos) {
            return back()->with('error', "La mesa {$mesa->codigo} tiene pedidos activos y no puede editarse.");
        }

        // 🔸 No permitir ponerla 'disponible' si tiene comensales asignados
        if (
            $mesa->estado === 'ocupada' &&
            $request->estado === 'disponible' &&
            $mesa->comensales()->count() > 0
        ) {

            return back()->with('error', "La mesa {$mesa->codigo} tiene comensales y no puede pasar a disponible.");
        }

        // 🔸 Si cambia de zona, validar que la nueva zona esté abierta
        if ($mesa->zona_id !== $request->zona_id) {
            $zonaNueva = Zona::find($request->zona_id);
            if (!$zonaNueva->estaAbierta()) {
                return back()->with('error', "La zona seleccionada ({$zonaNueva->nombre}) está cerrada.");
            }
        }

        // 🔸 Actualizar mesa
        $mesa->update($request->validated());

        return redirect()->route('mesas.index')->with('success', 'Mesa actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        // 🚫 No permitir eliminar mesas ocupadas
        if ($mesa->estado === 'ocupada') {
            return redirect()->route('mesas.index')
                ->with('error', "❌ La mesa {$mesa->codigo} está ocupada y no puede eliminarse.");
        }

        // 🚫 No permitir eliminar mesas ligadas a pedidos activos
        if ($mesa->pedidos()->where('estado', '!=', 'cerrado')->exists()) {
            return redirect()->route('mesas.index')
                ->with('error', "❌ La mesa {$mesa->codigo} tiene pedidos activos y no puede eliminarse.");
        }

        // 🗑️ Si está libre, sí eliminar
        $mesa->delete();

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa eliminada correctamente.');
    }

    /**
     * Vista principal para asignar mesas.
     * Incluye filtro por personas y sugerencias de combinación.
     */
    public function asignar(Request $request)
    {
        $personas = $request->input('personas');

        // Cargar zonas con mesas disponibles
        $zonas = Zona::with(['mesas' => function ($q) {
            $q->where('estado', 'disponible');
        }])->get();

        $sugerencias = [];

        if ($personas) {
            foreach ($zonas->filter(fn($z) => $z->estaAbierta()) as $zona) {

                $combinaciones = $this->buscarCombinaciones($zona->mesas, $personas);

                if (!empty($combinaciones)) {
                    $sugerencias[$zona->nombre] = $combinaciones;
                }
            }
        }


        return view('mesas.asignar', [
            'zonas' => $zonas,
            'personas' => $personas,
            'sugerencias' => $sugerencias,
            'horaActual' => now()->format('H:i:s'),
        ]);
    }

    /**
     * Buscar combinaciones de mesas que juntas cumplan con la capacidad.
     * No usa cascadas ni elimina nada automáticamente.
     */
    private function buscarCombinaciones($mesas, $personas)
    {
        $lista = $mesas->toArray();
        $combinaciones = [];

        for ($i = 0; $i < count($lista); $i++) {
            $total = $lista[$i]['capacidad'] + $lista[$i]['sillas_extra'];
            $ids = [$lista[$i]['id']];

            // 👉 1) Si una mesa sola es suficiente (mesa de 6, 8, etc.)
            if ($total >= $personas) {
                $combinaciones[] = [
                    'mesas' => [$lista[$i]['id']],
                    'total_capacidad' => $total
                ];
                continue; // ir a la siguiente mesa
            }

            // 👉 2) Combinaciones
            for ($j = $i + 1; $j < count($lista); $j++) {

                $total += $lista[$j]['capacidad'] + $lista[$j]['sillas_extra'];
                $ids[] = $lista[$j]['id'];

                if ($total >= $personas) {
                    $combinaciones[] = [
                        'mesas' => $ids,
                        'total_capacidad' => $total
                    ];
                    break;
                }
            }
        }

        // 👉 Ordenar: individuales primero, luego combinadas
        return collect($combinaciones)
            ->sortBy(function ($c) {
                return count($c['mesas']); // 1 primero
            })
            ->sortBy('total_capacidad')    // menor capacidad primero
            ->values()
            ->all();
    }


    public function asignarMesas(Request $request)
    {
        try {
            DB::beginTransaction();

            // 🔸 Validaciones básicas
            if (empty($request->mesas)) {
                throw new \Exception("No se seleccionaron mesas para asignar.");
            }

            if ($request->num_comensales <= 0) {
                throw new \Exception("Debes indicar al menos 1 comensal.");
            }

            // 🔹 Calcular capacidad total considerando sillas_extra
            $capacidadTotal = 0;
            $mensajeMesas = [];

            foreach ($request->mesas as $mesaId) {
                $mesa = Mesa::findOrFail($mesaId);
                $capacidadMesa = $mesa->capacidad + $mesa->sillas_extra;
                $capacidadTotal += $capacidadMesa;
                $mensajeMesas[] = "{$mesa->codigo} (capacidad {$capacidadMesa})";
            }

            // ⚠️ Validar si se excede la capacidad
            if ($request->num_comensales > $capacidadTotal) {
                $faltantes = $request->num_comensales - $capacidadTotal;

                // 🔸 Agregar sillas extra a la primera mesa del grupo
                $mesaPrincipal = Mesa::find($request->mesas[0]);
                $nuevasSillas = min(5, $mesaPrincipal->sillas_extra + $faltantes);

                // Si se puede agregar (sin rebasar el límite de 5 extras)
                if ($nuevasSillas <= 5) {
                    $mesaPrincipal->update(['sillas_extra' => $nuevasSillas]);

                    $capacidadTotal += $faltantes;
                } else {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        "⚠️ No puedes agregar más de 5 sillas extra por mesa.\n" .
                            "Capacidad disponible: {$capacidadTotal}, solicitada: {$request->num_comensales}."
                    );
                }
            }

            // 🔸 Crear pedido inicial con num_comensales actualizado
            $pedido = Pedido::create([
                'usuario_id'     => Auth::id(),
                'estado'         => 'pendiente',
                'total'          => 0,
                'num_comensales' => $request->num_comensales,
                'modo_cuenta'    => 'completa',
            ]);

            // 🔸 Asociar mesas y marcarlas ocupadas
            foreach ($request->mesas as $mesaId) {
                $mesa = Mesa::findOrFail($mesaId);

                if ($mesa->estado !== 'disponible') {
                    throw new \Exception("La mesa {$mesa->codigo} no está disponible.");
                }

                MesaPedido::create([
                    'mesa_id'   => $mesa->id,
                    'pedido_id' => $pedido->id,
                ]);

                $mesa->update(['estado' => 'ocupada']);
            }

            // 🔸 Crear los comensales (uno por cada persona)
            for ($i = 1; $i <= $request->num_comensales; $i++) {
                Comensale::create([
                    'pedido_id' => $pedido->id,
                    'numero'    => $i,
                    'nombre'    => null,
                ]);
            }

            DB::commit();

            return redirect()->route('pedidos.edit', $pedido->id)
                ->with('success', "Mesa asignada correctamente con {$request->num_comensales} comensales. 🪑 Acompaña a los clientes a su mesa y entrega el menú.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al asignar mesa: ' . $e->getMessage());
        }
    }


    public function agregarSillas(Request $request)
    {
        try {
            DB::beginTransaction();

            $mesa = Mesa::findOrFail($request->mesa_id);
            $mesa->sillas_extra += $request->cantidad;
            $mesa->save();

            // ✅ Crear el pedido directamente
            $pedido = Pedido::create([
                'usuario_id' => Auth::id(),
                'estado' => 'pendiente',
                'total' => 0,
                'abierta_en' => now(),
            ]);

            // Asociar la mesa
            $pedido->mesas()->attach($mesa->id);

            // Cambiar estado de la mesa
            $mesa->update(['estado' => 'ocupada']);

            DB::commit();

            return redirect()
                ->route('pedidos.index')
                ->with('success', "✅ Se agregaron {$request->cantidad} sillas extra a la mesa {$mesa->codigo}. 
                               Se asignó el pedido automáticamente (Mesa ocupada).");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', '❌ No se pudieron agregar las sillas ni crear el pedido: ' . $e->getMessage());
        }
    }



    /**
     * Crear un pedido combinando varias mesas.
     * (sin cascada, con validaciones manuales)
     */
    public function crearPedidoMultiple(Request $request)
    {
        $mesasIds = $request->input('mesas', []);

        if (empty($mesasIds)) {
            return back()->with('error', 'No se seleccionaron mesas para el pedido.');
        }

        DB::transaction(function () use ($mesasIds) {
            $pedido = Pedido::create([
                'usuario_id' => Auth::id(),
                'estado' => 'en_preparacion',
                'total' => 0,
            ]);

            foreach ($mesasIds as $idMesa) {
                $mesa = Mesa::find($idMesa);

                if (!$mesa || $mesa->estado !== 'disponible') {
                    throw new \Exception("La mesa con ID {$idMesa} no está disponible.");
                }

                // Insert manual sin cascade
                DB::table('mesa_pedidos')->insert([
                    'mesa_id' => $mesa->id,
                    'pedido_id' => $pedido->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Cambiar estado de la mesa
                $mesa->update(['estado' => 'ocupada']);
            }

            session()->flash('success', 'Pedido creado con múltiples mesas combinadas.');
            redirect()->route('pedidos.edit', $pedido);
        });

        return redirect()->route('pedidos.index');
    }
}

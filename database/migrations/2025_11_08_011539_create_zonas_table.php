<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();
            // Nombre único de la zona
            $table->string('nombre', 50)->unique()
                ->comment('Nombre de la zona: Terraza, Barra, Interior, etc.');

            // Descripción opcional
            $table->string('descripcion', 150)->nullable();

            // Estado general (si la zona está habilitada para asignar mesas)
            $table->boolean('activa')->default(true)
                ->comment('Determina si la zona está disponible para asignar mesas');

            // 🕒 Horarios configurables
            $table->time('hora_apertura')->nullable()
                ->comment('Hora de apertura de la zona, ej: 08:00');
            $table->time('hora_cierre')->nullable()
                ->comment('Hora de cierre de la zona, ej: 23:00');

            // Días activos en formato JSON
            $table->json('dias_activos')->nullable()
                ->comment('Días de operación: ["Lun","Mar","Mie","Jue","Vie","Sab","Dom"]');

            // Color o estilo visual (para dashboards o planos de mesa)
            $table->string('color_hex', 10)->default('#4CAF50')
                ->comment('Color visual para representar la zona en mapas o métricas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};

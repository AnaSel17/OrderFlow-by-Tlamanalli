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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();

           // Código único: M01, B02, T03...
        $table->string('codigo', 20)->unique();

        // Relación con Zonas (1 zona tiene muchas mesas)
        $table->foreignId('zona_id')->constrained('zonas');

        // Número de sillas fijas por mesa
        $table->smallInteger('capacidad')->default(2);

        // Sillas adicionales (temporales)
        $table->smallInteger('sillas_extra')->default(0);

        // Estado general de la mesa
        $table->enum('estado', ['disponible', 'ocupada', 'reservada', 'mantenimiento'])
            ->default('disponible');

        // Tipo de espacio (mesa regular, barra, terraza)
        $table->enum('tipo', ['mesa', 'barra', 'terraza'])->default('mesa');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};

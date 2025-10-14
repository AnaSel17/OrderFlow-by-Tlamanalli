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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mesa_id')->constrained('mesas');   // sin cascadas
            $table->foreignId('usuario_id')->constrained('users'); // sin cascadas

            $table->string('estado', 20)
                  ->default('pendiente')
                  ->comment('pendiente, en_preparacion, listo, entregado, cerrado, cancelado');

            $table->decimal('total', 12, 2);
            $table->decimal('propina', 12, 2)->nullable();

            $table->timestamp('abierta_en')->useCurrent();
            $table->timestamp('cerrada_en')->nullable();

            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

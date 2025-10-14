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
        Schema::create('cuentas_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cuenta_id')->constrained('cuentas');             // sin cascadas
            $table->foreignId('detalle_id')->constrained('detalles_pedido');    // sin cascadas

            $table->decimal('cantidad_asignada', 12, 3);
            $table->decimal('subtotal_asignado', 12, 2);

            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_detalles');
    }
};

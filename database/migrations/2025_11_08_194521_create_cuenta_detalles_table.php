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
        Schema::create('cuenta_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cuenta_id')->constrained('cuentas');
            $table->foreignId('detalle_id')->constrained('detalle_pedidos');

            $table->foreignId('comensal_id')->nullable()->constrained('comensales');

            $table->decimal('cantidad_asignada', 12, 3);
            $table->decimal('precio_unitario', 12, 2);
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

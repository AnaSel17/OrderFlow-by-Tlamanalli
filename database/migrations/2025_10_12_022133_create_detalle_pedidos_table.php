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
        Schema::create('detalles_pedido', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_id')->constrained('pedidos');   // sin cascadas
            $table->foreignId('producto_id')->constrained('productos'); // sin cascadas

            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->string('notas', 200)->nullable();

            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};

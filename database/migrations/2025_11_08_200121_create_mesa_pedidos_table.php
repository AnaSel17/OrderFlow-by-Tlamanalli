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
        Schema::create('mesa_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')
            ->nullable()
          ->constrained('mesas')
          ->restrictOnDelete()
          ->restrictOnUpdate();

             $table->foreignId('pedido_id')
          ->constrained('pedidos')
          ->restrictOnDelete()
          ->restrictOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesa_pedidos');
    }
};

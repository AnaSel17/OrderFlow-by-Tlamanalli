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
        Schema::create('comensales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos'); // sin cascadas
            $table->unsignedSmallInteger('numero')->comment('Número del comensal dentro del pedido');
            $table->string('nombre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comensales');
    }
};

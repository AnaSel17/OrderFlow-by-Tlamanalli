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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')
                ->unique()
                ->constrained('productos'); // sin cascadas

            $table->integer('stock_actual');
            $table->integer('punto_reorden');

            // Estado automático: 'Suficiente', 'Bajo' o 'Agotado'
            $table->enum('estado', ['Suficiente', 'Bajo', 'Agotado'])
                ->default('Suficiente');
                
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};

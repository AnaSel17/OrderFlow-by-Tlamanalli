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
        Schema::create('corte_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->decimal('efectivo', 12, 2)->default(0);
            $table->decimal('tarjeta', 12, 2)->default(0);
            $table->decimal('transferencia', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte_cajas');
    }
};

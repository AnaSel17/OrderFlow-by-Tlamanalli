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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('comensal_id')->nullable()->constrained('comensales');
            $table->foreignId('usuario_id')->nullable()->constrained('users');

            $table->enum('tipo', ['completa', 'comensal']);

            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('propina', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->string('estado', 12)
                ->default('abierta')
                ->comment('abierta, parcial, pagada, cancelada');

            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};

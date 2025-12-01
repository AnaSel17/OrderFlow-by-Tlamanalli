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

            // Usuario responsable (normalmente el mesero)
            $table->foreignId('usuario_id')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->restrictOnUpdate();

            $table->string('estado', 20)
                  ->default('pendiente')
                  ->comment('pendiente, enviado_cocina, en_preparacion, listo, listo_para_cobrar, cerrado, pagado, cancelado');

            $table->unsignedSmallInteger('num_comensales')->default(1);

            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('propina', 12, 2)->nullable();

            $table->timestamp('abierta_en')->useCurrent();
            $table->timestamp('cerrada_en')->nullable();

            $table->enum('modo_cuenta', ['completa', 'dividida'])
          ->default('completa')
          ->comment('Define si el pedido se cobrará completo o dividido por comensal');
            $table->enum('tipo', ['mesa', 'llevar'])->default('mesa');

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

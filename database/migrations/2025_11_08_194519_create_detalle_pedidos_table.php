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
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_id')->constrained('pedidos');   // sin cascadas
            $table->foreignId('producto_id')->constrained('productos'); // sin cascadas

            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0)->after('precio_unitario');

            $table->string('notas', 200)->nullable();

            $table->enum('estado', ['pendiente', 'enviado_cocina', 'en_preparacion', 'listo', 'entregado', 'cancelado', 'pagado'])
              ->default('pendiente')
              ->after('notas');

            $table->foreignId('comensal_id')
            ->nullable()
            ->constrained('comensales');

            $table->foreignId('comanda_id')
              ->nullable()
              ->constrained('comandas')
              ->nullOnDelete()
              ->after('pedido_id');

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

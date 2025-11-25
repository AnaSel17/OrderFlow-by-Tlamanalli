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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cuenta_id')->constrained('cuentas'); // sin cascadas

            $table->string('metodo', 30)->comment('efectivo, tarjeta, transferencia, vales, mixto');
            $table->decimal('monto', 12, 2);
            $table->string('referencia', 60)->nullable();
            $table->timestamp('pagado_en')->useCurrent();

            $table->foreignId('recibido_por')->constrained('users'); // sin cascadas
            $table->string('notas', 200)->nullable();

            $table->timestamps();

            
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 150);
            $table->string('sku', 60)->unique();
            $table->decimal('precio', 12, 2);
            $table->boolean('activo')->default(true);

            $table->foreignId('categoria_id')
                ->constrained('categorias'); // sin cascadas

            $table->timestamps();

            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

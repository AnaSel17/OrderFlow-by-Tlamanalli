<?php

// yyyy_mm_dd_xxxxxx_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Se cambia el nombre de la tabla a 'users' para coincidir con el diagrama.
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('user_id'); // PK: user_id
            
            // FK: rol_id. Asumiendo que existe una tabla 'roles' con una llave primaria 'rol_id'.
            $table->foreignId('id_rol')->constrained('roles', 'id_rol');

            $table->string('user_nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable(); // Se mantiene como opcional (nullable).
            
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Columna agregada.
            $table->string('password');
            $table->string('telefono')->nullable(); // Se mantiene como opcional.
            $table->string('user_estado')->default('activo'); // Se cambió 'status' por 'user_estado'.

            $table->rememberToken(); // Columna agregada para 'remember_token'.
            $table->timestamp('last_login_at')->nullable(); // Columna agregada.

            // Estas dos columnas son creadas por el método timestamps().
            // created_at y updated_at
            $table->timestamps();

            // Columna agregada para borrado lógico (soft delete).
            $table->softDeletes(); // Crea la columna 'deleted_at'.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
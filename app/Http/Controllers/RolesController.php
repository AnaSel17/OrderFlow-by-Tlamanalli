<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
}

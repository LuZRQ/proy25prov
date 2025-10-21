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
        Schema::create('Usuario', function (Blueprint $table) {
            $table->string('ciUsuario', 8)->primary();
            $table->string('nombre', 50);
            $table->string('apellido', 60);
            $table->string('correo')->unique();
            $table->string('telefono', 8)->nullable();
            $table->string('usuario', 50)->unique();
            $table->string('contrasena');
            $table->boolean('estado')->default(true);
            $table->timestamp('fechaRegistro')->useCurrent();
            $table->unsignedBigInteger('rolId');

            $table->foreign('rolId')->references('idRol')->on('Rol')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuario');
    }
};

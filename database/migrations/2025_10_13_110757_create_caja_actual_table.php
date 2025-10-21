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
        Schema::create('caja_actual', function (Blueprint $table) {
            $table->bigIncrements('id_caja'); // id de la caja actual
            $table->string('ciUsuario', 8); // quien abrió la caja
            $table->decimal('fondo_inicial', 10, 2)->default(0);
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->timestamp('fecha_apertura')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Relación con Usuario
            $table->foreign('ciUsuario')
                ->references('ciUsuario')
                ->on('Usuario')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_actual');
    }
};

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
        Schema::create('Pedido', function (Blueprint $table) {
            $table->id('idPedido');
            $table->string('ciUsuario', 20);
            $table->string('direccion', 255);
            $table->string('estado', 50)->default('pendiente');
            $table->decimal('total', 10, 2);
            $table->timestamp('fechaCreacion')->useCurrent();

            $table->foreign('ciUsuario')->references('ciUsuario')->on('Usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Pedido');
    }
};

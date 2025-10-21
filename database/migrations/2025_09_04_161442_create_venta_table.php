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
        Schema::create('Venta', function (Blueprint $table) {
            $table->id('idVenta');
            $table->unsignedBigInteger('idPedido');
            $table->decimal('montoTotal', 10, 2);
            $table->timestamp('fechaPago')->nullable();

            $table->foreign('idPedido')->references('idPedido')->on('Pedido')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Venta');
    }
};

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
        Schema::create('DetallePedido', function (Blueprint $table) {
            $table->id('idDetallePedido');
            $table->unsignedBigInteger('idPedido');
            $table->unsignedBigInteger('idProducto');
            $table->integer('cantidad');
            $table->decimal('subtotal', 10, 2);

            $table->foreign('idPedido')->references('idPedido')->on('Pedido')->onDelete('cascade');
            $table->foreign('idProducto')->references('idProducto')->on('Producto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DetallePedido');
    }
};

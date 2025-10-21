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
        Schema::create('CierreCaja', function (Blueprint $table) {
            $table->id('idCierre');

            // Relación con Usuario
            $table->string('ciUsuario', 8);
            $table->foreign('ciUsuario')
                ->references('ciUsuario')
                ->on('Usuario')
                ->onDelete('cascade');

            $table->decimal('fondo_inicial', 10, 2)->default(0);
            $table->decimal('total_efectivo', 10, 2)->default(0);
            $table->decimal('total_tarjeta', 10, 2)->default(0);
            $table->decimal('total_qr', 10, 2)->default(0);
            $table->decimal('total_caja', 10, 2)->default(0);

            $table->timestamp('fecha_apertura')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CierreCaja');
    }
};

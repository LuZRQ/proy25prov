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
        Schema::create('Auditoria', function (Blueprint $table) {
            $table->id('idAuditoria');
            $table->string('accion', 100);
            $table->timestamp('fechaHora')->useCurrent();
            $table->string('ciUsuario', 20);
            $table->string('ipOrigen', 50)->nullable();
            $table->string('modulo', 100);

            $table->foreign('ciUsuario')->references('ciUsuario')->on('Usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Auditoria');
    }
};

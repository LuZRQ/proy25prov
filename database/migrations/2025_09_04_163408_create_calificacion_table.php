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
        Schema::create('Calificacion', function (Blueprint $table) {
            $table->id('idCalificacion');
            $table->string('ciUsuario', 20);
            $table->tinyInteger('calificacion');
            $table->string('comentario', 255)->nullable();
            $table->timestamp('fecha')->useCurrent();

            $table->foreign('ciUsuario')->references('ciUsuario')->on('Usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Calificacion');
    }
};

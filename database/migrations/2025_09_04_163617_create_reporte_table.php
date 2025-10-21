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
        Schema::create('Reporte', function (Blueprint $table) {
            $table->id('idReporte');
            $table->string('tipo', 50);
            $table->string('periodo', 50)->nullable();
            $table->string('generadoPor', 50);
            $table->timestamp('fechaGeneracion')->useCurrent();
            $table->string('archivo', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Reporte');
    }
};

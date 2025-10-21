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
        Schema::table('Producto', function (Blueprint $table) {
            if (!Schema::hasColumn('Producto', 'imagen')) {
                $table->string('imagen')->nullable()->after('categoriaId');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Producto', function (Blueprint $table) {
            // Solo eliminar la columna si existe
            if (Schema::hasColumn('Producto', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });
    }
};

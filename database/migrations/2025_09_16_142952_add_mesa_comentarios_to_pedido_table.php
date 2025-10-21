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
        Schema::table('Pedido', function (Blueprint $table) {
            // Eliminar columna que ya no se usa
            if (Schema::hasColumn('Pedido', 'direccion')) {
                $table->dropColumn('direccion');
            }

            // Agregar columna mesa solo si NO existe
            if (!Schema::hasColumn('Pedido', 'mesa')) {
                $table->string('mesa', 10)->after('ciUsuario');
            }

            // Agregar columna comentarios solo si NO existe
            if (!Schema::hasColumn('Pedido', 'comentarios')) {
                $table->string('comentarios', 255)->nullable()->after('mesa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Pedido', function (Blueprint $table) {
            if (Schema::hasColumn('Pedido', 'mesa')) {
                $table->dropColumn('mesa');
            }
            if (Schema::hasColumn('Pedido', 'comentarios')) {
                $table->dropColumn('comentarios');
            }

            // Restaurar direccion solo si no existe
            if (!Schema::hasColumn('Pedido', 'direccion')) {
                $table->string('direccion', 255)->after('ciUsuario');
            }
        });
    }
};

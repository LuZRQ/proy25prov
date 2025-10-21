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
        Schema::table('Venta', function (Blueprint $table) {
            $table->string('metodo_pago', 50)->after('fechaPago')->nullable();
            $table->decimal('pago_cliente', 10, 2)->after('metodo_pago')->nullable();
            $table->decimal('cambio', 10, 2)->after('pago_cliente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Venta', function (Blueprint $table) {
            $table->dropColumn(['metodo_pago', 'pago_cliente', 'cambio']);
        });
    }
};

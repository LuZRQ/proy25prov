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
            $table->decimal('efectivo_real', 10, 2)->default(0)->after('montoTotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Venta', function (Blueprint $table) {
            $table->dropColumn('efectivo_real');
        });
    }
};

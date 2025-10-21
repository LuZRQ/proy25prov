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
            $table->boolean('estado')->default(1)->after('stock_inicial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Producto', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};

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
        Schema::table('iklimoptdpi_data', function (Blueprint $table) {
            $table->unsignedBigInteger('id_bulan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklimoptdpi_data', function (Blueprint $table) {
            $table->unsignedBigInteger('id_bulan')->nullable(false)->change();
        });
    }
};
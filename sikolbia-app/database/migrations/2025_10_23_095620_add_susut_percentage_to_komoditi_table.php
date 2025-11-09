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
        Schema::table('komoditi', function (Blueprint $table) {
            $table->decimal('susut_min_persen', 5, 2)->default(5.00)->after('harga_rata_per_kg');
            $table->decimal('susut_max_persen', 5, 2)->default(10.00)->after('susut_min_persen');
            $table->text('susut_keterangan')->nullable()->after('susut_max_persen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komoditi', function (Blueprint $table) {
            $table->dropColumn(['susut_min_persen', 'susut_max_persen', 'susut_keterangan']);
        });
    }
};

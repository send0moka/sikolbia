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
        Schema::create('pola_musiman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_komoditi');
            $table->tinyInteger('bulan');
            $table->decimal('faktor_musiman', 6, 4)->default(1.0000);
            $table->decimal('volatilitas_historis', 6, 4)->nullable();
            $table->decimal('trend_5_tahun', 6, 4)->nullable();
            $table->decimal('confidence_interval_lower', 6, 4)->nullable();
            $table->decimal('confidence_interval_upper', 6, 4)->nullable();
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();

            $table->unique(['kode_komoditi', 'bulan'], 'unique_commodity_month');
            $table->foreign('kode_komoditi')->references('kode_komoditi')->on('komoditi')->onDelete('cascade');
            $table->index(['bulan'], 'idx_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pola_musiman');
    }
};

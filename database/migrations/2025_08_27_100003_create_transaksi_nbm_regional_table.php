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
        Schema::create('transaksi_nbm_regional', function (Blueprint $table) {
            $table->id();
            $table->string('kode_provinsi', 10);
            $table->string('nama_provinsi', 100);
            $table->string('kode_kelompok');
            $table->string('kode_komoditi');
            $table->integer('tahun');
            $table->tinyInteger('bulan')->nullable();
            $table->decimal('produksi_lokal', 12, 4)->nullable();
            $table->decimal('konsumsi_regional', 12, 4)->nullable();
            $table->decimal('surplus_defisit', 12, 4)->nullable();
            $table->decimal('harga_regional', 10, 2)->nullable();
            $table->timestamps();

            $table->index(['kode_provinsi', 'kode_komoditi', 'tahun', 'bulan'], 'idx_regional_lookup');
            $table->foreign('kode_kelompok')->references('kode')->on('kelompok')->onDelete('cascade');
            $table->foreign('kode_komoditi')->references('kode_komoditi')->on('komoditi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_nbm_regional');
    }
};

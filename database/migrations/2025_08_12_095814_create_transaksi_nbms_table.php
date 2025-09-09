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
        Schema::create('transaksi_nbms', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelompok');
            $table->string('kode_komoditi');
            $table->integer('tahun');
            $table->enum('status_angka', ['tetap', 'sementara', 'sangat sementara']);
            $table->decimal('masukan', 12, 4)->nullable();
            $table->decimal('keluaran', 12, 4)->nullable();
            $table->decimal('impor', 12, 4)->nullable();
            $table->decimal('ekspor', 12, 4)->nullable();
            $table->decimal('perubahan_stok', 12, 4)->nullable();
            $table->decimal('pakan', 12, 4)->nullable();
            $table->decimal('bibit', 12, 4)->nullable();
            $table->decimal('makanan', 12, 4)->nullable();
            $table->decimal('bukan_makanan', 12, 4)->nullable();
            $table->decimal('tercecer', 12, 4)->nullable();
            $table->decimal('penggunaan_lain', 12, 4)->nullable();
            $table->decimal('bahan_makanan', 12, 4)->nullable();
            $table->decimal('kg_tahun', 12, 4)->nullable();
            $table->decimal('gram_hari', 12, 4)->nullable();
            $table->decimal('kalori_hari', 12, 4)->nullable();
            $table->decimal('protein_hari', 12, 4)->nullable();
            $table->decimal('lemak_hari', 10, 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_nbms');
    }
};

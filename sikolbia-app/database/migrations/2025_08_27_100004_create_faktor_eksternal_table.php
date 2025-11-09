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
        Schema::create('faktor_eksternal', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->tinyInteger('bulan');
            $table->decimal('pdb_nominal', 15, 2)->nullable();
            $table->decimal('pdb_riil', 15, 2)->nullable();
            $table->decimal('inflasi_umum', 6, 3)->nullable();
            $table->decimal('inflasi_pangan', 6, 3)->nullable();
            $table->decimal('nilai_tukar_rupiah', 10, 4)->nullable();
            $table->decimal('harga_minyak_dunia', 8, 2)->nullable();
            $table->decimal('indeks_harga_pangan_dunia', 8, 2)->nullable();
            $table->decimal('el_nino_index', 6, 3)->nullable();
            $table->decimal('iod_index', 6, 3)->nullable();
            $table->decimal('curah_hujan_mm', 8, 2)->nullable();
            $table->decimal('suhu_rata_celsius', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['tahun', 'bulan'], 'unique_period');
            $table->index(['tahun', 'bulan'], 'idx_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktor_eksternal');
    }
};

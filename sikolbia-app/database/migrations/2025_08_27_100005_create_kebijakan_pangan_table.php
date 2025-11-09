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
        Schema::create('kebijakan_pangan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_berlaku');
            $table->enum('jenis_kebijakan', ['impor', 'ekspor', 'subsidi', 'harga', 'stok', 'produksi']);
            $table->json('kode_komoditi_terdampak')->nullable();
            $table->text('deskripsi');
            $table->enum('dampak_prediksi', ['positif', 'negatif', 'netral'])->default('netral');
            $table->decimal('magnitude', 3, 2)->default(1.00);
            $table->integer('durasi_bulan')->nullable();
            $table->enum('status', ['aktif', 'berakhir', 'dibatalkan'])->default('aktif');
            $table->timestamps();

            $table->index(['tanggal_berlaku'], 'idx_policy_date');
            $table->index(['jenis_kebijakan', 'status'], 'idx_policy_type_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebijakan_pangan');
    }
};

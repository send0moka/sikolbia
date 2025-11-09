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
        Schema::create('transaksi_susenas', function (Blueprint $table) {
            $table->id(); // Auto increment primary key
            $table->string('kd_kelompokbps'); // Foreign key ke tb_kelompokbps
            $table->string('kd_komoditibps'); // Foreign key ke tb_komoditibps
            $table->year('tahun'); // Tahun data
            $table->decimal('konsumsikuantity', 10, 2); // Konsumsi dalam kuantitas
            $table->string('Satuan', 50)->nullable(); // Satuan konsumsi
            $table->decimal('konsumsinilai', 10, 2)->nullable(); // Nilai konsumsi
            $table->decimal('konsumsigizi', 10, 2)->nullable(); // Gizi konsumsi
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('kd_kelompokbps')->references('kd_kelompokbps')->on('tb_kelompokbps')->onDelete('cascade');
            $table->foreign('kd_komoditibps')->references('kd_komoditibps')->on('tb_komoditibps')->onDelete('cascade');
            
            // Index untuk performance
            $table->index(['kd_kelompokbps', 'kd_komoditibps', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_susenas');
    }
};

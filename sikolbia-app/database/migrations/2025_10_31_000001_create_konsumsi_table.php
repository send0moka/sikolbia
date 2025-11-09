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
        Schema::create('konsumsi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_komoditi');
            $table->integer('tahun');
            $table->tinyInteger('bulan');
            $table->decimal('konsumsi_per_kapita', 15, 4)->nullable()->comment('Konsumsi per kapita dalam kg/tahun');
            $table->decimal('konsumsi_total', 20, 4)->nullable()->comment('Total konsumsi dalam ton');
            $table->bigInteger('jumlah_penduduk')->nullable()->comment('Jumlah penduduk');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('kode_komoditi');
            $table->index('tahun');
            $table->index('bulan');
            $table->unique(['kode_komoditi', 'tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsumsi');
    }
};

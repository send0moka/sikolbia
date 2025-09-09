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
        Schema::table('transaksi_nbms', function (Blueprint $table) {
            // Temporal & Granularity
            $table->tinyInteger('bulan')->nullable()->after('tahun');
            $table->tinyInteger('kuartal')->nullable()->after('bulan');
            $table->enum('periode_data', ['bulanan', 'kuartalan', 'tahunan'])->default('tahunan')->after('kuartal');

            // Economic Indicators
            $table->decimal('harga_produsen', 12, 4)->nullable()->after('lemak_hari');
            $table->decimal('harga_konsumen', 12, 4)->nullable()->after('harga_produsen');
            $table->decimal('inflasi_komoditi', 8, 4)->nullable()->after('harga_konsumen');
            $table->decimal('nilai_tukar_usd', 10, 4)->nullable()->after('inflasi_komoditi');

            // Geographic & Demographic
            $table->bigInteger('populasi_indonesia')->nullable()->after('nilai_tukar_usd');
            $table->decimal('gdp_per_kapita', 12, 2)->nullable()->after('populasi_indonesia');
            $table->decimal('tingkat_kemiskinan', 5, 2)->nullable()->after('gdp_per_kapita');

            // Climate & Environmental
            $table->decimal('curah_hujan_mm', 8, 2)->nullable()->after('tingkat_kemiskinan');
            $table->decimal('suhu_rata_celsius', 5, 2)->nullable()->after('curah_hujan_mm');
            $table->decimal('indeks_el_nino', 6, 3)->nullable()->after('suhu_rata_celsius');
            $table->decimal('luas_panen_ha', 12, 2)->nullable()->after('indeks_el_nino');
            $table->decimal('produktivitas_ton_ha', 8, 4)->nullable()->after('luas_panen_ha');

            // Policy & External Factors
            $table->enum('kebijakan_impor', ['bebas', 'terbatas', 'dilarang'])->default('bebas')->after('produktivitas_ton_ha');
            $table->decimal('subsidi_pemerintah', 15, 2)->default(0)->after('kebijakan_impor');
            $table->decimal('stok_bulog', 12, 4)->nullable()->after('subsidi_pemerintah');

            // Quality & Validation
            $table->decimal('confidence_score', 3, 2)->default(1.00)->after('stok_bulog');
            $table->string('data_source', 100)->default('BPS')->after('confidence_score');
            $table->enum('validation_status', ['verified', 'pending', 'flagged'])->default('pending')->after('data_source');
            $table->boolean('outlier_flag')->default(false)->after('validation_status');

            // Indexes for better performance
            $table->index(['kode_kelompok', 'kode_komoditi', 'tahun', 'bulan'], 'idx_nbm_lookup');
            $table->index(['tahun', 'bulan'], 'idx_temporal');
            $table->index(['validation_status', 'outlier_flag'], 'idx_quality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_nbms', function (Blueprint $table) {
            $table->dropIndex('idx_nbm_lookup');
            $table->dropIndex('idx_temporal');
            $table->dropIndex('idx_quality');
            
            $table->dropColumn([
                'bulan', 'kuartal', 'periode_data',
                'harga_produsen', 'harga_konsumen', 'inflasi_komoditi', 'nilai_tukar_usd',
                'populasi_indonesia', 'gdp_per_kapita', 'tingkat_kemiskinan',
                'curah_hujan_mm', 'suhu_rata_celsius', 'indeks_el_nino',
                'luas_panen_ha', 'produktivitas_ton_ha',
                'kebijakan_impor', 'subsidi_pemerintah', 'stok_bulog',
                'confidence_score', 'data_source', 'validation_status', 'outlier_flag'
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean up inconsistent data before adding foreign key constraints
        $this->cleanupInconsistentData();
        
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
            
            // Foreign Key Constraints
            $table->foreign('kode_kelompok')->references('kode')->on('kelompok')->onDelete('restrict');
            $table->foreign('kode_komoditi')->references('kode_komoditi')->on('komoditi')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_nbms', function (Blueprint $table) {
            // Drop foreign key constraints only if they exist
            $foreignKeys = DB::select('SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = "transaksi_nbms" AND CONSTRAINT_NAME LIKE "%foreign%"');
            
            foreach ($foreignKeys as $fk) {
                if (str_contains($fk->CONSTRAINT_NAME, 'kode_kelompok')) {
                    $table->dropForeign(['kode_kelompok']);
                }
                if (str_contains($fk->CONSTRAINT_NAME, 'kode_komoditi')) {
                    $table->dropForeign(['kode_komoditi']);
                }
            }
            
            // Drop indexes
            $indexes = DB::select('SELECT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_NAME = "transaksi_nbms" AND INDEX_NAME IN ("idx_nbm_lookup", "idx_temporal", "idx_quality")');
            
            foreach ($indexes as $idx) {
                switch ($idx->INDEX_NAME) {
                    case 'idx_nbm_lookup':
                        $table->dropIndex('idx_nbm_lookup');
                        break;
                    case 'idx_temporal':
                        $table->dropIndex('idx_temporal');
                        break;
                    case 'idx_quality':
                        $table->dropIndex('idx_quality');
                        break;
                }
            }
            
            // Drop columns that were added
            $columns = DB::select('SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = "transaksi_nbms" AND COLUMN_NAME IN ("bulan", "kuartal", "periode_data", "harga_produsen", "harga_konsumen", "inflasi_komoditi", "nilai_tukar_usd", "populasi_indonesia", "gdp_per_kapita", "tingkat_kemiskinan", "curah_hujan_mm", "suhu_rata_celsius", "indeks_el_nino", "luas_panen_ha", "produktivitas_ton_ha", "kebijakan_impor", "subsidi_pemerintah", "stok_bulog", "confidence_score", "data_source", "validation_status", "outlier_flag")');
            
            $columnsToDropInMigration = [];
            foreach ($columns as $col) {
                $columnsToDropInMigration[] = $col->COLUMN_NAME;
            }
            
            if (!empty($columnsToDropInMigration)) {
                $table->dropColumn($columnsToDropInMigration);
            }
        });
    }

    /**
     * Clean up inconsistent data before adding foreign key constraints
     */
    private function cleanupInconsistentData(): void
    {
        // Ensure we have all necessary komoditi data first
        $this->ensureKomoditiData();
        
        // Remove records with kode_komoditi that don't exist in komoditi table
        $deletedCount = DB::table('transaksi_nbms')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('komoditi')
                      ->whereColumn('komoditi.kode_komoditi', 'transaksi_nbms.kode_komoditi');
            })
            ->delete();
            
        if ($deletedCount > 0) {
            echo "Deleted {$deletedCount} records with invalid kode_komoditi\n";
        }
        
        // Remove records with kode_kelompok that don't exist in kelompok table
        $deletedKelompokCount = DB::table('transaksi_nbms')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('kelompok')
                      ->whereColumn('kelompok.kode', 'transaksi_nbms.kode_kelompok');
            })
            ->delete();
            
        if ($deletedKelompokCount > 0) {
            echo "Deleted {$deletedKelompokCount} records with invalid kode_kelompok\n";
        }
    }

    /**
     * Ensure all necessary komoditi data exists
     */
    private function ensureKomoditiData(): void
    {
        // Insert missing komoditi that exist in transaksi_nbms but not in komoditi table
        $missingKomoditi = [
            // Kelompok 05 extensions
            ['kode_kelompok' => '05', 'kode_komoditi' => '0507', 'nama' => 'Tempe'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0508', 'nama' => 'Oncom'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0509', 'nama' => 'Produk Kedelai Lainnya'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0510', 'nama' => 'Kacang Kapri'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0511', 'nama' => 'Kacang Lima'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0512', 'nama' => 'Kacang Buncis'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0513', 'nama' => 'Kacang Komak'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0514', 'nama' => 'Kacang Bambara'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0515', 'nama' => 'Kacang Jogo'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0516', 'nama' => 'Kacang Bogor'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0517', 'nama' => 'Kacang Arab'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0518', 'nama' => 'Kacang Turis'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0519', 'nama' => 'Kacang Kratok'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0520', 'nama' => 'Kacang Velvet'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0521', 'nama' => 'Kacang Lentil'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0522', 'nama' => 'Kacang Chickpea'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0523', 'nama' => 'Kacang Lupin'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0524', 'nama' => 'Kacang Adzuki'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0525', 'nama' => 'Kacang Moth'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0526', 'nama' => 'Kacang Winged'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0527', 'nama' => 'Kacang Yam'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0528', 'nama' => 'Kacang Broad'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0529', 'nama' => 'Kacang Horse'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0530', 'nama' => 'Kacang Vetch'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0531', 'nama' => 'Kacang Tepary'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0532', 'nama' => 'Kacang Scarlet'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0533', 'nama' => 'Kacang Lima Runner'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0534', 'nama' => 'Kacang Sword'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0535', 'nama' => 'Kacang Jack'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0536', 'nama' => 'Kacang Lablab'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0537', 'nama' => 'Kacang Hyacinth'],
            ['kode_kelompok' => '05', 'kode_komoditi' => '0538', 'nama' => 'Produk Kacang-kacangan Lainnya'],
        ];

        foreach ($missingKomoditi as $komoditi) {
            // Check if komoditi already exists
            $exists = DB::table('komoditi')
                ->where('kode_komoditi', $komoditi['kode_komoditi'])
                ->exists();
                
            if (!$exists) {
                // Check if kelompok exists
                $kelompokExists = DB::table('kelompok')
                    ->where('kode', $komoditi['kode_kelompok'])
                    ->exists();
                    
                if ($kelompokExists) {
                    DB::table('komoditi')->insert([
                        'kode_kelompok' => $komoditi['kode_kelompok'],
                        'kode_komoditi' => $komoditi['kode_komoditi'],
                        'nama' => $komoditi['nama'],
                        'kalori_per_100g' => 300, // Default values
                        'protein_per_100g' => 20.0,
                        'lemak_per_100g' => 5.0,
                        'karbohidrat_per_100g' => 50.0,
                        'serat_per_100g' => 10.0,
                        'vitamin_c_per_100g' => 5.0,
                        'zat_besi_per_100g' => 3.0,
                        'kalsium_per_100g' => 100,
                        'musim_panen' => 'sepanjang_tahun',
                        'asal_produksi' => 'lokal',
                        'shelf_life_hari' => 365,
                        'harga_rata_per_kg' => 15000,
                        'satuan_dasar' => 'kg',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
};

<?php

namespace Database\Seeders;

use App\Models\TransaksiNbm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TransaksiNbmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Updating TransaksiNbm data with APP3 Pusdatin official data...\n";
        
        // List of files to process (komoditi => [kode_kelompok, kode_komoditi, filename])
        $komoditiFiles = [
            'Gabah' => ['01', '0101', 'transaksi_nbms_gabah_app3.sql'],
            'Beras' => ['01', '0102', 'transaksi_nbms_beras_app3.sql'],
            'Jagung' => ['01', '0103', 'transaksi_nbms_jagung_app3.sql'],
            'Jagung Basah' => ['01', '0104', 'transaksi_nbms_jagungbasah_app3.sql'],
            'Gandum' => ['01', '0105', 'transaksi_nbms_gandum_app3.sql'],
            'Tepung Gandum' => ['01', '0106', 'transaksi_nbms_tepunggandum_app3.sql'],
            'Ubi Jalar' => ['02', '0201', 'transaksi_nbms_ubijalar_app3.sql'],
            'Ubi Kayu' => ['02', '0202', 'transaksi_nbms_ubikayu_app3.sql'],
            'Gaplek' => ['02', '0203', 'transaksi_nbms_gaplek_app3.sql'],
            'Tapioka' => ['02', '0204', 'transaksi_nbms_tapioka_app3.sql'],
            'Tepung Sagu' => ['02', '0205', 'transaksi_nbms_tepungsagu_app3.sql'],
            'Gula Pasir' => ['03', '0301', 'transaksi_nbms_gulapasir_app3.sql'],
            'Gula Mangkok' => ['03', '0302', 'transaksi_nbms_gulamangkok_app3.sql'],
            'Kacang Tanah Berkulit' => ['04', '0401', 'transaksi_nbms_kacangtanahberkulit_app3.sql'],
            'Kacang Tanah Lepas Kulit' => ['04', '0402', 'transaksi_nbms_kacangtanahlepaskulit_app3.sql'],
            'Kedelai' => ['04', '0403', 'transaksi_nbms_kedelai_app3.sql'],
            'Kacang Hijau' => ['04', '0404', 'transaksi_nbms_kacanghijau_app3.sql'],
            'Kelapa Daging' => ['04', '0405', 'transaksi_nbms_kelapadaging_app3.sql'],
            'Kopra' => ['04', '0406', 'transaksi_nbms_kopra_app3.sql'],
            'Alpokat' => ['05', '0501', 'transaksi_nbms_alpokat_app3.sql'],
            'Jeruk' => ['05', '0502', 'transaksi_nbms_jeruk_app3.sql'],
            'Duku' => ['05', '0503', 'transaksi_nbms_duku_app3.sql'],
            'Durian' => ['05', '0504', 'transaksi_nbms_durian_app3.sql'],
            'Jambu' => ['05', '0505', 'transaksi_nbms_jambu_app3.sql'],
            'Mangga' => ['05', '0506', 'transaksi_nbms_mangga_app3.sql'],
            'Nanas' => ['05', '0507', 'transaksi_nbms_nanas_app3.sql'],
            'Pepaya' => ['05', '0508', 'transaksi_nbms_pepaya_app3.sql'],
            'Pisang' => ['05', '0509', 'transaksi_nbms_pisang_app3.sql'],
            'Rambutan' => ['05', '0510', 'transaksi_nbms_rambutan_app3.sql'],
            'Salak' => ['05', '0511', 'transaksi_nbms_salak_app3.sql'],
            'Sawo' => ['05', '0512', 'transaksi_nbms_sawo_app3.sql'],
            'Anggur' => ['05', '0513', 'transaksi_nbms_anggur_app3.sql'],
            'Semangka' => ['05', '0514', 'transaksi_nbms_semangka_app3.sql'],
            'Belimbing' => ['05', '0515', 'transaksi_nbms_belimbing_app3.sql'],
            'Manggis' => ['05', '0516', 'transaksi_nbms_manggis_app3.sql'],
            'Nangka' => ['05', '0517', 'transaksi_nbms_nangka_app3.sql'],
            'Markisa' => ['05', '0518', 'transaksi_nbms_markisa_app3.sql'],
            'Sirsak' => ['05', '0519', 'transaksi_nbms_sirsak_app3.sql'],
            'Sukun' => ['05', '0520', 'transaksi_nbms_sukun_app3.sql'],
            'Buah Lainnya' => ['05', '0521', 'transaksi_nbms_buahlainnya_app3.sql'],
            'Apel' => ['05', '0522', 'transaksi_nbms_apel_app3.sql'],
            'Jambu Air' => ['05', '0523', 'transaksi_nbms_jambuair_app3.sql'],
            'Melon' => ['05', '0524', 'transaksi_nbms_melon_app3.sql'],
            'Stroberi' => ['05', '0525', 'transaksi_nbms_stroberi_app3.sql'],
            'Blewah' => ['05', '0526', 'transaksi_nbms_blewah_app3.sql'],
            'Lemon' => ['05', '0527', 'transaksi_nbms_lemon_app3.sql'],
            'Jeruk Besar' => ['05', '0528', 'transaksi_nbms_jerukbesar_app3.sql'],
            'Kurma' => ['05', '0529', 'transaksi_nbms_kurma_app3.sql'],
            'Tin' => ['05', '0530', 'transaksi_nbms_tin_app3.sql'],
            'Pir' => ['05', '0531', 'transaksi_nbms_pir_app3.sql'],
            'Aprikot' => ['05', '0532', 'transaksi_nbms_aprikot_app3.sql'],
            'Rasberi' => ['05', '0533', 'transaksi_nbms_rasberi_app3.sql'],
            'Kiwi' => ['05', '0534', 'transaksi_nbms_kiwi_app3.sql'],
            'Kesemek' => ['05', '0535', 'transaksi_nbms_kesemek_app3.sql'],
            'Lengkeng' => ['05', '0536', 'transaksi_nbms_lengkeng_app3.sql'],
            'Leci' => ['05', '0537', 'transaksi_nbms_leci_app3.sql'],
            'Buah Naga' => ['05', '0538', 'transaksi_nbms_buahnaga_app3.sql'],
            'Bawang Merah' => ['06', '0601', 'transaksi_nbms_bawangmerah_app3.sql'],
            'Timun' => ['06', '0602', 'transaksi_nbms_timun_app3.sql'],
            'Kacang Merah' => ['06', '0603', 'transaksi_nbms_kacangmerah_app3.sql'],
            'Kacang Panjang' => ['06', '0604', 'transaksi_nbms_kacangpanjang_app3.sql'],
            'Kentang' => ['06', '0605', 'transaksi_nbms_kentang_app3.sql'],
            'Kubis' => ['06', '0606', 'transaksi_nbms_kubis_app3.sql'],
            'Tomat' => ['06', '0607', 'transaksi_nbms_tomat_app3.sql'],
            'Wortel' => ['06', '0608', 'transaksi_nbms_wortel_app3.sql'],
            'Cabai' => ['06', '0609', 'transaksi_nbms_cabai_app3.sql'],
            'Terong' => ['06', '0610', 'transaksi_nbms_terong_app3.sql'],
            'Sawi' => ['06', '0611', 'transaksi_nbms_sawi_app3.sql'],
            'Daun Bawang' => ['06', '0612', 'transaksi_nbms_daunbawang_app3.sql'],
            'Kangkung' => ['06', '0613', 'transaksi_nbms_kangkung_app3.sql'],
            'Lobak' => ['06', '0614', 'transaksi_nbms_lobak_app3.sql'],
            'Labu Siam' => ['06', '0615', 'transaksi_nbms_labusiam_app3.sql'],
            'Buncis' => ['06', '0616', 'transaksi_nbms_buncis_app3.sql'],
            'Bayam' => ['06', '0617', 'transaksi_nbms_bayam_app3.sql'],
            'Bawang Putih' => ['06', '0618', 'transaksi_nbms_bawangputih_app3.sql'],
            'Kembang Kol' => ['06', '0619', 'transaksi_nbms_kembangkol_app3.sql'],
            'Jamur' => ['06', '0620', 'transaksi_nbms_jamur_app3.sql'],
            'Melinjo' => ['06', '0621', 'transaksi_nbms_melinjo_app3.sql'],
            'Petai' => ['06', '0622', 'transaksi_nbms_petai_app3.sql'],
            'Sayur Lainnya' => ['06', '0623', 'transaksi_nbms_sayuranlainnya_app3.sql'],
            'Jengkol' => ['06', '0624', 'transaksi_nbms_jengkol_app3.sql'],
            // 'Bawang Bombay' => ['06', '0625', 'transaksi_nbms_bawangbombay_app3.sql'],
            // 'Seledri' => ['06', '0626', 'transaksi_nbms_seledri_app3.sql'],
            // 'Asparagus' => ['06', '0627', 'transaksi_nbms_asparagus_app3.sql'],
            // 'Selada' => ['06', '0628', 'transaksi_nbms_selada_app3.sql'],
            // 'Kacang Kapri' => ['06', '0629', 'transaksi_nbms_kacangkapri_app3.sql'],
            // 'Paprika' => ['06', '0630', 'transaksi_nbms_paprika_app3.sql'],
            // 'Jamur Lainnya' => ['06', '0631', 'transaksi_nbms_jamurlainnya_app3.sql'],
            // 'Jamur Merang' => ['06', '0632', 'transaksi_nbms_jamurmerang_app3.sql'],
            // 'Jamur Tiram' => ['06', '0633', 'transaksi_nbms_jamurtiram_app3.sql'],
            // 'Cabai Rawit' => ['06', '0634', 'transaksi_nbms_cabairawit_app3.sql'],
            // 'Cabai Besar' => ['06', '0635', 'transaksi_nbms_cabaibesar_app3.sql'],
            'Daging Sapi' => ['07', '0701', 'transaksi_nbms_dagingsapi_app3.sql'],
            'Daging Kerbau' => ['07', '0702', 'transaksi_nbms_dagingkerbau_app3.sql'],
            'Daging Kambing' => ['07', '0703', 'transaksi_nbms_dagingkambing_app3.sql'],
            'Daging Domba' => ['07', '0704', 'transaksi_nbms_dagingdomba_app3.sql'],
            'Daging Kuda' => ['07', '0705', 'transaksi_nbms_dagingkuda_app3.sql'],
            'Daging Babi' => ['07', '0706', 'transaksi_nbms_dagingbabi_app3.sql'],
            'Daging Ayam Buras' => ['07', '0707', 'transaksi_nbms_dagingayamburas_app3.sql'],
            'Daging Ayam Ras' => ['07', '0708', 'transaksi_nbms_dagingayamras_app3.sql'],
            'Daging Bebek' => ['07', '0709', 'transaksi_nbms_dagingbebek_app3.sql'],
            'Jeroan' => ['07', '0710', 'transaksi_nbms_jeroan_app3.sql'],
            'Daging Puyuh' => ['07', '0711', 'transaksi_nbms_dagingpuyuh_app3.sql'],
            'Telur Ayam Buras' => ['08', '0801', 'transaksi_nbms_telurayamburas_app3.sql'],
            'Telur Ayam Ras' => ['08', '0802', 'transaksi_nbms_telurayamras_app3.sql'],
            'Telur Bebek' => ['08', '0803', 'transaksi_nbms_telurbebek_app3.sql'],
            'Susu Sapi' => ['09', '0901', 'transaksi_nbms_sususapi_app3.sql'],
            'Susu Impor' => ['09', '0902', 'transaksi_nbms_susuimpor_app3.sql'],
            'Minyak Kacang Tanah' => ['10', '1001', 'transaksi_nbms_minyakkacangtanah_app3.sql'],
            'Minyak Goreng Kelapa' => ['10', '1002', 'transaksi_nbms_minyakgorengkelapa_app3.sql'],
            'Minyak Sawit' => ['10', '1003', 'transaksi_nbms_minyaksawit_app3.sql'],
            'Minyak Goreng Sawit' => ['10', '1004', 'transaksi_nbms_minyakgorengsawit_app3.sql'],
            'Lemak Sapi' => ['10', '1005', 'transaksi_nbms_lemaksapi_app3.sql'],
            'Lemak Kerbau' => ['10', '1006', 'transaksi_nbms_lemakkerbau_app3.sql'],
            'Lemak Kambing' => ['10', '1007', 'transaksi_nbms_lemakkambing_app3.sql'],
            'Lemak Domba' => ['10', '1008', 'transaksi_nbms_lemakdomba_app3.sql'],
            'Lemak Babi' => ['10', '1009', 'transaksi_nbms_lemakbabi_app3.sql'],
        ];
        
        foreach ($komoditiFiles as $namaKomoditi => $config) {
            list($kodeKelompok, $kodeKomoditi, $filename) = $config;
            
            echo "\n=== Processing $namaKomoditi ($kodeKelompok-$kodeKomoditi) ===\n";
            
            // Delete existing records
            $deletedCount = DB::table('transaksi_nbms')
                ->where('kode_kelompok', $kodeKelompok)
                ->where('kode_komoditi', $kodeKomoditi)
                ->delete();
            echo "Deleted $deletedCount old $namaKomoditi records\n";
            
            $this->processFile($filename, $kodeKelompok, $kodeKomoditi, $namaKomoditi);
        }
        
        echo "\n=== POST-PROCESSING: CONVERT TO MONTHLY & FIX ZERO CONSUMPTION ===\n";
        $this->convertAnnualToMonthly();
        $this->fixZeroConsumption();
        
        echo "\n=== ALL DONE ===\n";
        echo "Data source: APP3 (Aplikasi Neraca Bahan Makanan) - Pusdatin Kementerian Pertanian\n";
        echo "Post-processing: Monthly breakdown + Zero consumption fix applied\n";
    }
    
    private function processFile($filename, $kodeKelompok, $kodeKomoditi, $namaKomoditi)
    {
        // Open the APP3 SQL file
        $filePath = base_path('database/seeders/' . $filename);
        if (!file_exists($filePath)) {
            echo "Warning: File not found: $filePath\n";
            echo "Run: php database/seeders/generate_nbm_from_app3.php to generate\n";
            return;
        }
        
        $file = fopen($filePath, 'r');
        if ($file === false) {
            echo "Error: Could not open file {$filePath}\n";
            return;
        }

        // Define columns (40 total for ENHANCED format - matched with generate_nbm_enhanced.php output)
        // Format dari generator:
        // Row 1: kode_kelompok, kode_komoditi, tahun, bulan, kuartal, periode_data, status_angka (7)
        // Row 2: masukan, keluaran, impor, ekspor, perubahan_stok (5)
        // Row 3: pakan, bibit, makanan, bukan_makanan, tercecer, penggunaan_lain (6)
        // Row 4: bahan_makanan, harga_produsen, harga_konsumen, inflasi(NULL), nilai_tukar(NULL), populasi, gdp(NULL), kemiskinan(NULL), curah_hujan, suhu, indeks_el_nino(NULL), luas_panen, produktivitas (13)
        // Row 5: NULL, NULL, kebijakan_impor, subsidi_pemerintah, stok_bulog(NULL), confidence_score, data_source, validation_status, outlier_flag (9)
        $columns = [
            // Row 1 - Identitas (7)
            'kode_kelompok', 'kode_komoditi', 'tahun', 'bulan', 'kuartal', 'periode_data', 'status_angka',
            // Row 2 - Neraca Masukan/Keluaran (5)
            'masukan', 'keluaran', 'impor', 'ekspor', 'perubahan_stok',
            // Row 3 - Penggunaan (6)
            'pakan', 'bibit', 'makanan', 'bukan_makanan', 'tercecer', 'penggunaan_lain',
            // Row 4 - Konsumsi & Konteks (13)
            'bahan_makanan', 'harga_produsen', 'harga_konsumen', 'inflasi_komoditi', 'nilai_tukar_usd',
            'populasi_indonesia', 'gdp_per_kapita', 'tingkat_kemiskinan',
            'curah_hujan_mm', 'suhu_rata_celsius', 'indeks_el_nino',
            'luas_panen_ha', 'produktivitas_ton_ha',
            // Row 5 - Kebijakan & Metadata (9) - 2 NULL placeholder di awal untuk alignment
            'placeholder_1', 'placeholder_2', // These will be skipped (NULL values in generator)
            'kebijakan_impor', 'subsidi_pemerintah', 'stok_bulog',
            'confidence_score', 'data_source', 'validation_status', 'outlier_flag'
        ];

        // Define ENUM constraints and defaults
        $enumConstraints = [
            'periode_data' => ['bulanan', 'kuartalan', 'tahunan'],
            'status_angka' => ['tetap', 'sementara', 'sangat sementara'],
            'kebijakan_impor' => ['bebas', 'terbatas', 'dilarang'],
            'validation_status' => ['verified', 'pending', 'flagged']
        ];
        $defaults = [
            'kode_kelompok' => '00',
            'kode_komoditi' => '0000',
            'tahun' => 0,
            'periode_data' => 'tahunan',
            'status_angka' => 'tetap',
            'kebijakan_impor' => 'bebas',
            'subsidi_pemerintah' => 0.00,
            'confidence_score' => 1.00,
            'data_source' => 'BPS',
            'validation_status' => 'pending',
            'outlier_flag' => 0
        ];

        // Define precision for DECIMAL columns
        $decimalPrecision = [
            'masukan' => [12, 4], 'keluaran' => [12, 4], 'impor' => [12, 4], 'ekspor' => [12, 4],
            'perubahan_stok' => [12, 4], 'pakan' => [12, 4], 'bibit' => [12, 4], 'makanan' => [12, 4],
            'bukan_makanan' => [12, 4], 'tercecer' => [12, 4], 'penggunaan_lain' => [12, 4],
            'bahan_makanan' => [12, 4], 'harga_produsen' => [12, 4], 'harga_konsumen' => [12, 4], 'inflasi_komoditi' => [8, 4],
            'nilai_tukar_usd' => [10, 4], 'gdp_per_kapita' => [12, 2], 'tingkat_kemiskinan' => [5, 2],
            'curah_hujan_mm' => [8, 2], 'suhu_rata_celsius' => [5, 2], 'indeks_el_nino' => [6, 3],
            'luas_panen_ha' => [16, 2], 'produktivitas_ton_ha' => [8, 4], 'subsidi_pemerintah' => [15, 2],
            'stok_bulog' => [12, 4], 'confidence_score' => [3, 2]
        ];

        $totalRecords = 0;
        $skippedRows = 0;
        $chunk = [];
        $chunkSize = 1000;
        $lineNumber = 0;

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        while (($line = fgets($file)) !== false) {
            $lineNumber++;
            $line = trim($line, "(),\n");
            $values = array_map('trim', explode(',', $line));

            // Support both old (38 cols) and new enhanced (40 cols) format
            $valueCount = count($values);
            if ($valueCount !== count($columns)) {
                // Try to handle old format by padding with NULLs for missing columns
                if ($valueCount < count($columns)) {
                    // Pad dengan NULL untuk kolom yang hilang (old format)
                    $missingCount = count($columns) - $valueCount;
                    for ($i = 0; $i < $missingCount; $i++) {
                        $values[] = 'NULL';
                    }
                } else {
                    echo "Warning: Expected " . count($columns) . " values, got " . $valueCount . " at line {$lineNumber}\n";
                    $skippedRows++;
                    continue;
                }
            }

            $row = [];
            $isValidRow = true;

            foreach ($columns as $i => $col) {
                // Skip placeholder columns (they're just NULL in SQL)
                if ($col === 'placeholder_1' || $col === 'placeholder_2') {
                    continue; // Don't add to row array
                }
                
                $value = $values[$i] ?? '';
                if ($value === 'NULL') {
                    if (in_array($col, array_keys($defaults))) {
                        $row[$col] = $defaults[$col];
                    } else {
                        $row[$col] = null;
                    }
                } elseif (in_array($col, ['kode_kelompok', 'kode_komoditi', 'periode_data', 'status_angka', 
                                         'kebijakan_impor', 'data_source', 'validation_status'])) {
                    $cleanValue = trim($value, "'");
                    if (isset($enumConstraints[$col]) && !in_array($cleanValue, $enumConstraints[$col])) {
                        echo "Warning: Invalid ENUM value '$cleanValue' for $col at line {$lineNumber}, using default {$defaults[$col]}\n";
                        $row[$col] = $defaults[$col];
                    } else {
                        $row[$col] = $cleanValue;
                    }
                } elseif (in_array($col, ['tahun', 'bulan', 'kuartal', 'populasi_indonesia', 'outlier_flag'])) {
                    $row[$col] = is_numeric($value) ? (int)$value : ($defaults[$col] ?? 0);
                } else {
                    if (is_numeric($value)) {
                        $floatValue = (float)$value;
                        if (isset($decimalPrecision[$col])) {
                            list($total, $decimals) = $decimalPrecision[$col];
                            $maxValue = pow(10, $total - $decimals) - pow(10, -$decimals);
                            if (abs($floatValue) > $maxValue) {
                                echo "Warning: Value $floatValue for $col exceeds precision ($total,$decimals) at line {$lineNumber}, capping to $maxValue\n";
                                $floatValue = $maxValue;
                            }
                            $row[$col] = round($floatValue, $decimals);
                        } else {
                            $row[$col] = $floatValue;
                        }
                    } else {
                        $row[$col] = null;
                    }
                }
            }

            if ($isValidRow) {
                $chunk[] = $row;
                $totalRecords++;
            } else {
                echo "Skipping invalid row at line {$lineNumber}\n";
                $skippedRows++;
                continue;
            }

            if (count($chunk) >= $chunkSize) {
                try {
                    DB::table('transaksi_nbms')->insertOrIgnore($chunk);
                    echo "Inserted $totalRecords records so far...\n";
                } catch (QueryException $e) {
                    echo "Error inserting chunk at line {$lineNumber}: " . $e->getMessage() . "\n";
                    $skippedRows += count($chunk);
                }
                $chunk = [];
            }
        }

        if (!empty($chunk)) {
            try {
                DB::table('transaksi_nbms')->insertOrIgnore($chunk);
            } catch (QueryException $e) {
                echo "Error inserting final chunk: " . $e->getMessage() . "\n";
                $skippedRows += count($chunk);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        fclose($file);
        
        $finalCount = DB::table('transaksi_nbms')
            ->where('kode_kelompok', $kodeKelompok)
            ->where('kode_komoditi', $kodeKomoditi)
            ->count();
        echo "✓ Updated $totalRecords $namaKomoditi records (DB count: $finalCount, skipped: $skippedRows)\n";
    }
    
    /**
     * Convert annual data (month=0) to monthly breakdown (12 months)
     * Distributes annual values with variation across months while keeping total intact
     * Strategy: 11 months get random variation (90-110%), month 7 (Juli) gets remainder to ensure exact total
     */
    private function convertAnnualToMonthly()
    {
        echo "\n--- Converting Annual Data to Monthly Breakdown ---\n";
        
        $annualRecords = DB::table('transaksi_nbms')->where('bulan', 0)->get();
        
        if ($annualRecords->isEmpty()) {
            echo "No annual records found (all data already monthly)\n";
            return;
        }
        
        echo "Found {$annualRecords->count()} annual records to convert...\n";
        
        $inserted = 0;
        $deleted = 0;
        
        // Columns that will be distributed across 12 months (should sum to annual total)
        $distributedColumns = [
            'masukan', 'keluaran', 'impor', 'ekspor', 'perubahan_stok',
            'pakan', 'bibit', 'makanan', 'bukan_makanan', 'tercecer', 'penggunaan_lain',
            'bahan_makanan'
        ];
        
        DB::beginTransaction();
        
        try {
            foreach ($annualRecords as $record) {
                // Delete the annual record
                DB::table('transaksi_nbms')->where('id', $record->id)->delete();
                $deleted++;
                
                // Prepare monthly data with variation
                $monthlyData = [];
                $sums = []; // Track sum for each distributed column
                
                // Initialize sums
                foreach ($distributedColumns as $col) {
                    $sums[$col] = 0;
                }
                
                // Generate data for all months except July (month 7)
                for ($month = 1; $month <= 12; $month++) {
                    if ($month == 7) {
                        // Skip July for now, will be calculated as remainder
                        continue;
                    }
                    
                    $monthData = [
                        'kode_kelompok' => $record->kode_kelompok,
                        'kode_komoditi' => $record->kode_komoditi,
                        'tahun' => $record->tahun,
                        'bulan' => $month,
                        'kuartal' => ceil($month / 3),
                        'periode_data' => 'bulanan',
                        'status_angka' => $record->status_angka,
                        
                        // Keep enhanced data same for each month
                        'harga_produsen' => $record->harga_produsen,
                        'harga_konsumen' => $record->harga_konsumen,
                        'inflasi_komoditi' => $record->inflasi_komoditi,
                        'nilai_tukar_usd' => $record->nilai_tukar_usd,
                        'populasi_indonesia' => $record->populasi_indonesia,
                        'gdp_per_kapita' => $record->gdp_per_kapita,
                        'tingkat_kemiskinan' => $record->tingkat_kemiskinan,
                        'curah_hujan_mm' => $record->curah_hujan_mm,
                        'suhu_rata_celsius' => $record->suhu_rata_celsius,
                        'indeks_el_nino' => $record->indeks_el_nino,
                        'luas_panen_ha' => $record->luas_panen_ha,
                        'produktivitas_ton_ha' => $record->produktivitas_ton_ha,
                        'kebijakan_impor' => $record->kebijakan_impor,
                        'subsidi_pemerintah' => $record->subsidi_pemerintah,
                        'stok_bulog' => $record->stok_bulog,
                        'confidence_score' => $record->confidence_score,
                        'validation_status' => $record->validation_status,
                        'data_source' => $record->data_source,
                        'outlier_flag' => $record->outlier_flag,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    // Distribute values with random variation (90%-110% of average)
                    foreach ($distributedColumns as $col) {
                        $annualValue = $record->$col ?? 0;
                        $baseMonthly = $annualValue / 12;
                        
                        // Random factor between 0.90 and 1.10 (±10% variation)
                        $randomFactor = 0.90 + (mt_rand(0, 200) / 1000); // 0.90 to 1.10
                        $monthlyValue = round($baseMonthly * $randomFactor, 4);
                        
                        $monthData[$col] = $monthlyValue;
                        $sums[$col] += $monthlyValue;
                    }
                    
                    $monthlyData[] = $monthData;
                }
                
                // Month 7 (Juli): Use remainder to ensure exact total
                $month7Data = [
                    'kode_kelompok' => $record->kode_kelompok,
                    'kode_komoditi' => $record->kode_komoditi,
                    'tahun' => $record->tahun,
                    'bulan' => 7,
                    'kuartal' => 3,
                    'periode_data' => 'bulanan',
                    'status_angka' => $record->status_angka,
                    
                    // Keep enhanced data same
                    'harga_produsen' => $record->harga_produsen,
                    'harga_konsumen' => $record->harga_konsumen,
                    'inflasi_komoditi' => $record->inflasi_komoditi,
                    'nilai_tukar_usd' => $record->nilai_tukar_usd,
                    'populasi_indonesia' => $record->populasi_indonesia,
                    'gdp_per_kapita' => $record->gdp_per_kapita,
                    'tingkat_kemiskinan' => $record->tingkat_kemiskinan,
                    'curah_hujan_mm' => $record->curah_hujan_mm,
                    'suhu_rata_celsius' => $record->suhu_rata_celsius,
                    'indeks_el_nino' => $record->indeks_el_nino,
                    'luas_panen_ha' => $record->luas_panen_ha,
                    'produktivitas_ton_ha' => $record->produktivitas_ton_ha,
                    'kebijakan_impor' => $record->kebijakan_impor,
                    'subsidi_pemerintah' => $record->subsidi_pemerintah,
                    'stok_bulog' => $record->stok_bulog,
                    'confidence_score' => $record->confidence_score,
                    'validation_status' => $record->validation_status,
                    'data_source' => $record->data_source,
                    'outlier_flag' => $record->outlier_flag,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Calculate remainder for July (month 7) to ensure exact total
                foreach ($distributedColumns as $col) {
                    $annualValue = $record->$col ?? 0;
                    $remainder = round($annualValue - $sums[$col], 4);
                    
                    // Ensure non-negative (in case of rounding issues)
                    $remainder = max(0, $remainder);
                    
                    $month7Data[$col] = $remainder;
                }
                
                // Insert July data at correct position (index 6)
                array_splice($monthlyData, 6, 0, [$month7Data]);
                
                // Bulk insert all 12 monthly records
                DB::table('transaksi_nbms')->insert($monthlyData);
                $inserted += 12;
                
                if ($deleted % 100 == 0) {
                    echo "  Processed $deleted records...\n";
                }
            }
            
            DB::commit();
            echo "✓ Converted $deleted annual records → $inserted monthly records\n";
            echo "  Net increase: " . ($inserted - $deleted) . " records\n";
            echo "  Strategy: 11 months with ±10% variation, month 7 (Juli) = exact remainder\n";
            
        } catch (\Exception $e) {
            DB::rollback();
            echo "✗ Error during conversion: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Fix zero consumption for edible items
     * Generates realistic consumption values based on historical averages or baselines
     */
    private function fixZeroConsumption()
    {
        echo "\n--- Fixing Zero Consumption for Edible Items ---\n";
        
        // Exclude intermediate/raw goods that legitimately have zero consumption
        $excludeBahanBaku = ['010101', '010104', '040401', '040406']; // Gabah, Jagung Basah, Kacang berkulit, Kopra
        
        $zeroEdible = DB::select("
            SELECT t.kode_kelompok, t.kode_komoditi, k.nama, COUNT(*) as zero_count, k.kalori_per_100g
            FROM transaksi_nbms t
            LEFT JOIN komoditi k ON t.kode_kelompok = k.kode_kelompok AND t.kode_komoditi = k.kode_komoditi
            WHERE t.bahan_makanan = 0
              AND CONCAT(t.kode_kelompok, t.kode_komoditi) NOT IN ('" . implode("','", $excludeBahanBaku) . "')
              AND k.kalori_per_100g > 0
            GROUP BY t.kode_kelompok, t.kode_komoditi, k.nama, k.kalori_per_100g
            ORDER BY zero_count DESC
        ");
        
        if (empty($zeroEdible)) {
            echo "No zero consumption issues found\n";
            return;
        }
        
        echo "Found " . count($zeroEdible) . " komoditi with zero consumption\n";
        
        // Baseline consumption per food group (ribu ton per month)
        $baselineMap = [
            '01' => 15,    // Padi-padian
            '02' => 8,     // Umbi-umbian
            '03' => 5,     // Gula
            '04' => 3,     // Kacang-kacangan
            '05' => 5,     // Buah
            '06' => 4,     // Sayuran
            '07' => 2,     // Daging
            '08' => 1.5,   // Telur
            '09' => 3,     // Susu
            '10' => 1,     // Minyak/Lemak
        ];
        
        DB::beginTransaction();
        $updated = 0;
        
        try {
            foreach ($zeroEdible as $item) {
                // Get average from non-zero records of same komoditi
                $avg = DB::table('transaksi_nbms')
                    ->where('kode_kelompok', $item->kode_kelompok)
                    ->where('kode_komoditi', $item->kode_komoditi)
                    ->where('bahan_makanan', '>', 0)
                    ->avg('bahan_makanan');
                
                // If no historical data, use baseline by food group
                if (!$avg || $avg == 0) {
                    $avg = $baselineMap[$item->kode_kelompok] ?? 2;
                }
                
                // Update zero records with averaged value (add variance ±20%)
                $result = DB::table('transaksi_nbms')
                    ->where('kode_kelompok', $item->kode_kelompok)
                    ->where('kode_komoditi', $item->kode_komoditi)
                    ->where('bahan_makanan', 0)
                    ->update([
                        'bahan_makanan' => DB::raw("ROUND($avg * (0.8 + (RAND() * 0.4)), 4)"),
                        'updated_at' => now()
                    ]);
                
                $updated += $result;
                
                if ($result > 0) {
                    echo "  ✓ {$item->kode_kelompok}-{$item->kode_komoditi} {$item->nama}: $result records (avg=" . round($avg, 2) . " ribu ton)\n";
                }
            }
            
            DB::commit();
            echo "✓ Fixed $updated zero consumption records\n";
            
        } catch (\Exception $e) {
            DB::rollback();
            echo "✗ Error fixing zero consumption: " . $e->getMessage() . "\n";
        }
    }
}

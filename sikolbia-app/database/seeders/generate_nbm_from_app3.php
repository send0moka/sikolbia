<?php
/**
 * Script untuk generate SQL insert statements dari raw data APP3 NBM
 * Usage: php database/seeders/generate_nbm_from_app3.php <csv_file> <kode_kelompok> <kode_komoditi> <nama_output>
 * Example: php database/seeders/generate_nbm_from_app3.php raw-gabah-nbm-app3.csv 01 0101 gabah
 */

// Parse arguments
if ($argc < 5) {
    echo "Usage: php generate_nbm_from_app3.php <csv_file> <kode_kelompok> <kode_komoditi> <nama_output>\n";
    echo "Example: php generate_nbm_from_app3.php raw-beras-nbm-app3.csv 01 0102 beras\n";
    echo "Example: php generate_nbm_from_app3.php raw-gabah-nbm-app3.csv 01 0101 gabah\n";
    die();
}

$csvFileName = $argv[1];
$kodeKelompok = $argv[2];
$kodeKomoditi = $argv[3];
$namaOutput = $argv[4];

$inputCsv = 'C:\\Users\\jehia\\Downloads\\' . $csvFileName;
$outputSql = __DIR__ . '/transaksi_nbms_' . $namaOutput . '_app3.sql';

// Read CSV
$file = fopen($inputCsv, 'r');
if (!$file) {
    die("Error: Cannot open file $inputCsv\n");
}

// Skip header
$header = fgetcsv($file);
echo "Processing: $namaOutput (Kelompok: $kodeKelompok, Komoditi: $kodeKomoditi)\n";
echo "CSV Headers: " . implode(', ', $header) . "\n";

$sqlStatements = [];
$recordCount = 0;

while (($data = fgetcsv($file)) !== false) {
    // Skip empty rows
    if (empty($data[0])) continue;
    
    // Extract data from CSV
    $tahun = (int)$data[2]; // Tahun
    $masukan = floatval(str_replace(',', '', $data[3])); // Masukan
    $keluaran = floatval(str_replace(',', '', $data[4])); // Keluaran (produksi)
    $impor = floatval(str_replace(',', '', $data[5])); // Impor
    $ekspor = floatval(str_replace(',', '', $data[6])); // Ekspor (typo: Ekpor)
    $perubahanStok = floatval(str_replace(',', '', $data[7])); // Perubahan stok
    $pakan = floatval(str_replace(',', '', $data[8])); // Pakan
    $bibit = floatval(str_replace(',', '', $data[9])); // Bibit
    $makanan = floatval(str_replace(',', '', $data[10])); // Makanan
    $bukanMakanan = floatval(str_replace(',', '', $data[11])); // Bukan makanan
    $tercecer = floatval(str_replace(',', '', $data[12])); // Tercecer
    $penggunaanLain = floatval(str_replace(',', '', $data[13])); // Penggunaan lain
    $bahanMakanan = floatval(str_replace(',', '', $data[14])); // Bahan makanan
    $statusAngka = (int)$data[20]; // Status angka (0=tetap, 1=sementara, 2=sangat sementara)
    
    // Map status angka
    $statusAngkaText = 'tetap';
    if ($statusAngka == 1) $statusAngkaText = 'sementara';
    if ($statusAngka == 2) $statusAngkaText = 'sangat sementara';
    
    // Data APP3 adalah TAHUNAN (dalam ribu ton)
    // Simpan sebagai data tahunan, TIDAK dibagi per bulan
    $bulan = 0; // 0 untuk data tahunan
    $kuartal = 0; // 0 untuk data tahunan
    
    // Gunakan nilai tahunan langsung (dalam ribu ton)
    $masukanTahun = $masukan;
    $keluaranTahun = $keluaran;
    $imporTahun = $impor;
    $eksporTahun = $ekspor;
    $perubahanStokTahun = $perubahanStok;
    $pakanTahun = $pakan;
    $bibitTahun = $bibit;
    $makananTahun = $makanan;
    $bukanMakananTahun = $bukanMakanan;
    $tercecerTahun = $tercecer;
    $penggunaanLainTahun = $penggunaanLain;
    $bahanMakananTahun = $bahanMakanan;
        
    // Populate default values for other columns
    $populasi = 0; // Will be populated separately
    switch($tahun) {
        case 1993: $populasi = 187000000; break;
            case 1994: $populasi = 190500000; break;
            case 1995: $populasi = 193500000; break;
            case 1996: $populasi = 196500000; break;
            case 1997: $populasi = 199500000; break;
            case 1998: $populasi = 202500000; break;
            case 1999: $populasi = 205500000; break;
            case 2000: $populasi = 208500000; break;
            case 2001: $populasi = 211500000; break;
            case 2002: $populasi = 214500000; break;
            case 2003: $populasi = 217500000; break;
            case 2004: $populasi = 220500000; break;
            case 2005: $populasi = 223500000; break;
            case 2006: $populasi = 226500000; break;
            case 2007: $populasi = 229500000; break;
            case 2008: $populasi = 232500000; break;
            case 2009: $populasi = 235500000; break;
            case 2010: $populasi = 238500000; break;
            case 2011: $populasi = 241500000; break;
            case 2012: $populasi = 244500000; break;
            case 2013: $populasi = 247500000; break;
            case 2014: $populasi = 250500000; break;
            case 2015: $populasi = 253500000; break;
            case 2016: $populasi = 256500000; break;
            case 2017: $populasi = 259500000; break;
            case 2018: $populasi = 262500000; break;
            case 2019: $populasi = 265500000; break;
            case 2020: $populasi = 268500000; break;
            case 2021: $populasi = 271500000; break;
            case 2022: $populasi = 274500000; break;
            case 2023: $populasi = 277500000; break;
            case 2024: $populasi = 280500000; break;
        default: $populasi = 270000000;
    }
    
    // Build SQL insert statement - data TAHUNAN
    $sql = sprintf(
        "('%s', '%s', %d, %d, %d, 'tahunan', '%s', " . // kode_kelompok, kode_komoditi, tahun, bulan, kuartal, periode_data, status_angka
        "%.2f, %.2f, %.2f, %.2f, %.2f, " . // masukan, keluaran, impor, ekspor, perubahan_stok
        "%.2f, %.2f, %.2f, %.2f, %.2f, %.2f, " . // pakan, bibit, makanan, bukan_makanan, tercecer, penggunaan_lain
        "%.2f, NULL, NULL, NULL, NULL, %d, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, " . // bahan_makanan, ..., populasi_indonesia, ...
        "1.00, 'APP3 Pusdatin', 'verified', 0)", // confidence_score, data_source, validation_status, outlier_flag
        $kodeKelompok,
        $kodeKomoditi,
        $tahun,
        $bulan,
        $kuartal,
        $statusAngkaText,
        $masukanTahun,
        $keluaranTahun,
        $imporTahun,
        $eksporTahun,
        $perubahanStokTahun,
        $pakanTahun,
        $bibitTahun,
        $makananTahun,
        $bukanMakananTahun,
        $tercecerTahun,
        $penggunaanLainTahun,
        $bahanMakananTahun,
        $populasi
    );
    
    $sqlStatements[] = $sql;
    $recordCount++;
}

fclose($file);

// Write SQL file
file_put_contents($outputSql, implode(",\n", $sqlStatements));

echo "\n✓ Generated $recordCount TAHUNAN records (ribu ton)\n";
echo "✓ Output file: $outputSql\n";
echo "\nData summary:\n";
echo "- Komoditi: $namaOutput ($kodeKelompok-$kodeKomoditi)\n";
echo "- Periode: TAHUNAN (bulan=0, kuartal=0)\n";
echo "- Satuan: RIBU TON (000 ton)\n";
echo "- Records: $recordCount years\n";
echo "\nNext steps:\n";
echo "1. Review the generated SQL file\n";
echo "2. Run seeder: php artisan db:seed --class=TransaksiNbmSeeder\n";

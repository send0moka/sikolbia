<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Komoditi;

@mkdir(__DIR__ . '/../storage/reports', 0755, true);
$outFile = __DIR__ . '/../storage/reports/fallback_validation.csv';
$fp = fopen($outFile, 'w');
fputcsv($fp, ['kode_kelompok','kode_komoditi','used_fallback_count','historical_len','prediction','model_version','success']);

$koms = Komoditi::select('kode_kelompok','kode_komoditi')->get();

// choose base URL: inside container use nginx, otherwise localhost:8000
$baseUrl = 'http://localhost:8000';
if (file_exists('/.dockerenv')) {
    $baseUrl = 'http://nginx';
}
foreach ($koms as $k) {
    $kel = str_pad($k->kode_kelompok, 2, '0', STR_PAD_LEFT);
    $kom = str_pad($k->kode_komoditi, 4, '0', STR_PAD_LEFT);
    $req = ['kelompok' => $kel, 'komoditi' => $kom, 'bulan' => (int)date('n')];
    $cmd = "curl -sS -X POST {$baseUrl}/api/debug/prediksi-preview -H 'Content-Type: application/json' -d '" . addslashes(json_encode($req)) . "'";
    $out = shell_exec($cmd);
    $json = json_decode($out, true);
    if (!$json || empty($json['historical'])) {
        fputcsv($fp, [$kel, $kom, '0', '0', '', '', 'false']);
        echo "Skipped {$kel}-{$kom}: no historical\n";
        continue;
    }

    $hist = $json['historical'];
    $usedFallbackCount = 0;
    foreach ($hist as $h) {
        if (!empty($h['used_fallback_kalori_per_100g']) || !empty($h['used_fallback_grams_per_day'])) $usedFallbackCount++;
    }

    $payload = json_encode(['data_points' => $hist]);
    // ML API is exposed on host port 8082; within compose the service is sikolbia-ml-api on port 8000
    $mlUrl = (file_exists('/.dockerenv')) ? 'http://sikolbia-ml-api:8000' : 'http://localhost:8082';
    $cmd2 = "curl -sS -X POST {$mlUrl}/predict -H 'Content-Type: application/json' -d '" . addslashes($payload) . "'";
    $out2 = shell_exec($cmd2);
    $pred = json_decode($out2, true);
    $prediction = $pred['prediction'] ?? '';
    $modelVer = $pred['model_info']['version'] ?? '';
    $success = !empty($pred['success']) ? 'true' : 'false';

    fputcsv($fp, [$kel, $kom, $usedFallbackCount, count($hist), $prediction, $modelVer, $success]);
    echo "Processed {$kel}-{$kom}: pred=" . ($prediction ?: 'NA') . " fallback_count={$usedFallbackCount}\n";
}

fclose($fp);
echo "Report written to storage/reports/fallback_validation.csv\n";

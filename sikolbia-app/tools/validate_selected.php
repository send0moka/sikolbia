<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Pemerintah\PemerintahController;

@mkdir(__DIR__ . '/../storage/reports', 0755, true);
$outFile = __DIR__ . '/../storage/reports/selected_validation.csv';
$fp = fopen($outFile, 'w');
fputcsv($fp, ['kode_kelompok','kode_komoditi','has_valid_calories','avg_calories','prediction','model_version','success']);

$items = [
    ['10','1004'], // Minyak Goreng Sawit
    ['01','0101'], // Beras (example)
    ['02','0201']  // Jagung (example)
];

$controller = new PemerintahController();

foreach ($items as $it) {
    [$kel, $kom] = $it;
    $req = Request::create('/pemerintah/prediksi-nbm/run', 'POST', [
        'kelompok' => $kel,
        'komoditi' => $kom,
        'bulan' => (int)date('n'),
        'preview' => true
    ]);

    $resp = $controller->runPrediksi($req);
    $body = method_exists($resp, 'getContent') ? $resp->getContent() : json_encode(['success'=>false]);
    $json = json_decode($body, true);

    $hasValid = $json['has_valid_calories'] ?? false;
    $hist = $json['historical'] ?? [];
    $avg = 0;
    if (count($hist)) {
        $sum = 0; $cnt = 0;
        foreach ($hist as $h) { if (isset($h['kalori_hari'])) { $sum += floatval($h['kalori_hari']); $cnt++; } }
        $avg = $cnt ? round($sum/$cnt,2) : 0;
    }

    $prediction = '';
    $modelVer = '';
    $success = 'false';

    if ($hasValid) {
        $payload = json_encode(['data_points' => $hist]);
        // call ML service (use internal service name when inside container)
        $mlUrl = (file_exists('/.dockerenv')) ? 'http://sikolbia-ml-api:8000' : 'http://localhost:8082';
        $cmd = "curl -sS -X POST {$mlUrl}/predict -H 'Content-Type: application/json' -d '" . addslashes($payload) . "'";
        $out = shell_exec($cmd);
        $pred = json_decode($out, true);
        $prediction = $pred['prediction'] ?? ($pred['prediction']??'');
        $modelVer = $pred['model_info']['version'] ?? $pred['model_info']['model_version'] ?? '';
        $success = (!empty($pred) && (isset($pred['prediction']) || isset($pred['success']))) ? 'true' : 'false';
    }

    fputcsv($fp, [$kel, $kom, $hasValid ? 'true' : 'false', $avg, is_array($prediction)?json_encode($prediction):$prediction, $modelVer, $success]);
    echo "Processed {$kel}-{$kom}: has_valid=" . ($hasValid? 'yes':'no') . " avg={$avg} pred=" . ($prediction? 'ok':'') . "\n";
}

fclose($fp);
echo "Wrote report to storage/reports/selected_validation.csv\n";

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Pemerintah\PemerintahController;

$kel = $argv[1] ?? '10';
$kom = $argv[2] ?? '1004';
$bulan = isset($argv[3]) ? intval($argv[3]) : 3;

$previewFlag = isset($argv[4]) ? filter_var($argv[4], FILTER_VALIDATE_BOOLEAN) : true;
$req = Request::create('/pemerintah/prediksi-nbm/run', 'POST', [
    'kelompok' => $kel,
    'komoditi' => $kom,
    'bulan' => $bulan,
    'preview' => $previewFlag
]);

$controller = new PemerintahController();
$resp = $controller->runPrediksi($req);

if (method_exists($resp, 'getContent')) {
    echo $resp->getContent();
} else {
    echo json_encode(['success'=>false, 'message'=>'No response object']);
}

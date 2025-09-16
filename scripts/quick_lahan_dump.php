<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LahanTopik;
use App\Models\LahanVariabel;
use App\Models\LahanKlasifikasi;

$topiks = LahanTopik::select('id','deskripsi as nama')->orderBy('id')->limit(5)->get()->toArray();
$variabels = LahanVariabel::select('id','id_topik','deskripsi as nama','satuan','sorter')->orderBy('sorter')->limit(5)->get()->toArray();
$klasifikasis = LahanKlasifikasi::select('id','id_variabel','deskripsi as nama','sorter')->orderBy('sorter')->limit(5)->get()->toArray();

header('Content-Type: application/json');
echo json_encode([
  'topiks' => $topiks,
  'variabels' => $variabels,
  'klasifikasis' => $klasifikasis,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

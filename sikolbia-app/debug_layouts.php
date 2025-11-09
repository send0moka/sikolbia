<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\BenihPupukController;
use Illuminate\Http\Request;

try {
    $controller = new BenihPupukController();
    
    echo "=== TESTING LAYOUT TYPE 2 (Classifications as rows) ===\n";
    
    $request = new Request([
        'topik' => '2',  // Pupuk topic
        'variabels' => ['5'], // One variable
        'klasifikasis' => ['5', '6'], // Multiple classifications
        'selectedRegions' => ['11'],
        'tahun_awal' => '2014',
        'tahun_akhir' => '2016', 
        'selectedMonths' => [1,2,3,4,5,6,7,8,9,10,11,12],
        'tata_letak' => 'tipe_2',
        'yearMode' => 'range',
        'monthMode' => 'all'
    ]);
    
    $response = $controller->search($request);
    $data = $response->getData(true);
    
    echo "Tipe_2 Results:\n";
    echo "- Headers: " . json_encode($data['headers'] ?? []) . "\n";
    echo "- Data Count: " . (isset($data['data']) ? count($data['data']) : 0) . "\n";
    if (isset($data['data'])) {
        foreach ($data['data'] as $i => $row) {
            echo "  Row $i: {$row['label']} -> " . json_encode($row['values']) . "\n";
        }
    }
    
    echo "\n=== TESTING LAYOUT TYPE 3 (Months as columns) ===\n";
    
    $request = new Request([
        'topik' => '1',  // Benih topic  
        'variabels' => ['1'], // One variable
        'klasifikasis' => ['2', '3'], // Multiple classifications
        'selectedRegions' => ['11'],
        'tahun_awal' => '2014',
        'tahun_akhir' => '2016', 
        'selectedMonths' => [1,2,3,4,5,6,7,8,9,10,11,12],
        'tata_letak' => 'tipe_3',
        'yearMode' => 'range',
        'monthMode' => 'all'
    ]);
    
    $response = $controller->search($request);
    $data = $response->getData(true);
    
    echo "Tipe_3 Results:\n";
    echo "- Headers: " . json_encode($data['headers'] ?? []) . "\n";
    echo "- Data Count: " . (isset($data['data']) ? count($data['data']) : 0) . "\n";
    if (isset($data['data'])) {
        foreach ($data['data'] as $i => $row) {
            echo "  Row $i: {$row['label']} -> " . json_encode($row['values']) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

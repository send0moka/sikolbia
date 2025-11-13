<?php
/**
 * Test Laravel to ML API Integration
 * Run with: php test_laravel_ml_integration.php
 */

$ml_api_url = 'http://localhost:8083';

echo "============================================================\n";
echo "TESTING LARAVEL TO ML API INTEGRATION\n";
echo "============================================================\n\n";

// Test 1: Health Check
echo "📊 Test 1: Health Check\n";
try {
    $ch = curl_init("{$ml_api_url}/health");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status Code: $http_code\n";
    if ($http_code == 200) {
        $data = json_decode($response, true);
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        echo "✅ Health check PASSED\n";
    } else {
        echo "❌ Health check FAILED: $response\n";
    }
} catch (Exception $e) {
    echo "❌ Health check ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 2: Model Stats
echo "📊 Test 2: Model Stats\n";
try {
    $ch = curl_init("{$ml_api_url}/model/stats");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status Code: $http_code\n";
    if ($http_code == 200) {
        $data = json_decode($response, true);
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        echo "✅ Model stats PASSED\n";
    } else {
        echo "❌ Model stats FAILED: $response\n";
    }
} catch (Exception $e) {
    echo "❌ Model stats ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Prediction with proper payload
echo "📊 Test 3: NBM Prediction\n";
$payload = [
    'data_points' => [
        ['tahun' => 2024, 'bulan' => 1, 'kelompok' => '01', 'komoditi' => '0101', 'kalori_hari' => 500000],
        ['tahun' => 2024, 'bulan' => 2, 'kelompok' => '01', 'komoditi' => '0101', 'kalori_hari' => 520000],
        ['tahun' => 2024, 'bulan' => 3, 'kelompok' => '01', 'komoditi' => '0101', 'kalori_hari' => 510000],
        ['tahun' => 2024, 'bulan' => 4, 'kelompok' => '01', 'komoditi' => '0101', 'kalori_hari' => 530000],
        ['tahun' => 2024, 'bulan' => 5, 'kelompok' => '01', 'komoditi' => '0101', 'kalori_hari' => 540000],
        ['tahun' => 2024, 'bulan' => 6, 'kelompok' => '01', 'komoditi' => '0101', 'kalori_hari' => 550000]
    ],
    'n_periods' => 3
];

try {
    $ch = curl_init("{$ml_api_url}/predict");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status Code: $http_code\n";
    if ($http_code == 200) {
        $data = json_decode($response, true);
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        echo "✅ Prediction PASSED\n";
        echo "\n📈 Predictions: " . json_encode($data['predictions']) . "\n";
        echo "📊 Model Version: {$data['model_version']}\n";
    } else {
        echo "❌ Prediction FAILED: $response\n";
    }
} catch (Exception $e) {
    echo "❌ Prediction ERROR: " . $e->getMessage() . "\n";
}
echo "\n";

echo "============================================================\n";
echo "✅ INTEGRATION TESTS COMPLETED\n";
echo "============================================================\n";

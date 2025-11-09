<?php

// Test prediction payload format for FastAPI

$payload = [
    'data' => [
        [
            'tahun' => 2024,
            'bulan' => 1,
            'kelompok' => 'Padi - Padian',
            'komoditi' => 'Gabah',
            'kalori_hari' => 150.5
        ],
        [
            'tahun' => 2024,
            'bulan' => 2,
            'kelompok' => 'Padi - Padian',
            'komoditi' => 'Gabah',
            'kalori_hari' => 152.3
        ],
        [
            'tahun' => 2024,
            'bulan' => 3,
            'kelompok' => 'Padi - Padian',
            'komoditi' => 'Gabah',
            'kalori_hari' => 148.7
        ],
        [
            'tahun' => 2024,
            'bulan' => 4,
            'kelompok' => 'Padi - Padian',
            'komoditi' => 'Gabah',
            'kalori_hari' => 151.2
        ],
        [
            'tahun' => 2024,
            'bulan' => 5,
            'kelompok' => 'Padi - Padian',
            'komoditi' => 'Gabah',
            'kalori_hari' => 149.8
        ],
        [
            'tahun' => 2024,
            'bulan' => 6,
            'kelompok' => 'Padi - Padian',
            'komoditi' => 'Gabah',
            'kalori_hari' => 153.4
        ]
    ],
    'confidence_level' => 0.95
];

echo "Testing ML API prediction...\n";
echo "Payload:\n";
echo json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

try {
    $client = new \GuzzleHttp\Client();
    $response = $client->post('http://fastapi-ml:8000/predict', [
        'json' => $payload,
        'timeout' => 30,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]
    ]);
    
    $result = json_decode($response->getBody()->getContents(), true);
    
    echo "Response:\n";
    echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
    
    echo "\n✅ Prediction successful!\n";
    echo "Prediction: " . ($result['prediction'] ?? 'N/A') . " kcal/day\n";
    if (isset($result['confidence_interval'])) {
        echo "Confidence Interval: [" . $result['confidence_interval']['lower_bound'] . ", " . $result['confidence_interval']['upper_bound'] . "]\n";
    }
    
} catch (\GuzzleHttp\Exception\RequestException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        echo "Response body:\n";
        echo $e->getResponse()->getBody() . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

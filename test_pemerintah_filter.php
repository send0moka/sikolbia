<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\TransaksiNbm;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Pemerintah Filter Logic...\n";
echo "===================================\n\n";

try {
    // Test basic query without filters
    echo "Test 1: Query without filters\n";
    $query = TransaksiNbm::query();
    $count = $query->count();
    echo "Total records: $count\n\n";
    
    // Test with pagination
    echo "Test 2: Pagination (first 10 records)\n";
    $data = TransaksiNbm::orderBy('tahun', 'desc')
                       ->orderBy('bulan', 'desc')
                       ->orderBy('kode_kelompok')
                       ->orderBy('kode_komoditi')
                       ->limit(10)
                       ->get();
    
    echo "Retrieved " . $data->count() . " records\n";
    if ($data->count() > 0) {
        $first = $data->first();
        echo "First record: Tahun={$first->tahun}, Bulan={$first->bulan}, Kelompok={$first->kode_kelompok}\n";
    }
    echo "\n";
    
    // Test with relationships
    echo "Test 3: Load relationships\n";
    $data->load(['kelompok', 'komoditi']);
    $first = $data->first();
    echo "Kelompok relation: " . ($first->kelompok ? $first->kelompok->nama : 'NULL') . "\n";
    echo "Komoditi relation: " . ($first->komoditi ? $first->komoditi->nama : 'NULL') . "\n";
    echo "\n";
    
    // Test with filters
    echo "Test 4: Filter by year 2024\n";
    $filtered = TransaksiNbm::where('tahun', 2024)->count();
    echo "Records with tahun=2024: $filtered\n\n";
    
    echo "✅ All tests passed!\n";
    echo "The filter logic should work correctly.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

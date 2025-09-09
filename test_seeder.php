<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TransaksiNbm;
use Database\Seeders\TransaksiNbmSeeder;

echo "Testing TransaksiNbmSeeder...\n";

try {
    // Check current record count
    $initialCount = TransaksiNbm::count();
    echo "Initial records: {$initialCount}\n";
    
    // Run the seeder
    $seeder = new TransaksiNbmSeeder();
    $seeder->run();
    
    // Check final record count
    $finalCount = TransaksiNbm::count();
    echo "Final records: {$finalCount}\n";
    echo "Records added: " . ($finalCount - $initialCount) . "\n";
    
    // Show sample records
    echo "\nSample records:\n";
    $samples = TransaksiNbm::take(5)->get(['tahun', 'bulan', 'kode_komoditi', 'nama_komoditi', 'keluaran']);
    foreach ($samples as $sample) {
        echo "- {$sample->tahun}-{$sample->bulan}: {$sample->nama_komoditi} ({$sample->kode_komoditi}) = {$sample->keluaran}\n";
    }
    
    echo "\nSeeder test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

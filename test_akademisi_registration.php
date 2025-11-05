<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RegistrasiAkses;
use Illuminate\Support\Facades\Log;

try {
    echo "Testing Akademisi Registration...\n\n";
    
    // Test data
    $testData = [
        'nama_lengkap' => 'Test Akademisi User',
        'email' => 'test.akademisi@example.ac.id',
        'telepon' => '081234567890',
        'tipe_akses' => 'akademisi',
        'institusi' => 'Universitas Test',
        'jenjang_pendidikan' => 'S2',
        'program_studi' => 'Ilmu Gizi',
        'tujuan_penggunaan' => ['Penelitian Tesis', 'Analisis Data'],
        'deskripsi_kebutuhan' => 'Penelitian tentang ketahanan pangan',
        'status' => 'pending',
    ];
    
    // Try to create registration
    $registrasi = RegistrasiAkses::create($testData);
    
    echo "✅ Registration created successfully!\n";
    echo "ID: " . $registrasi->id . "\n";
    echo "Nama: " . $registrasi->nama_lengkap . "\n";
    echo "Email: " . $registrasi->email . "\n";
    echo "Tipe Akses: " . $registrasi->tipe_akses . "\n";
    echo "Institusi: " . $registrasi->institusi . "\n";
    echo "Status: " . $registrasi->status . "\n\n";
    
    // Clean up
    $registrasi->delete();
    echo "✅ Test registration deleted\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

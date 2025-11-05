<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\RegistrasiAkses;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating Test Registrasi Pemerintah...\n";
echo "======================================\n\n";

try {
    // Create a test registration
    $registrasi = RegistrasiAkses::create([
        'nama_lengkap' => 'Test Pemerintah User',
        'email' => 'jehianathayata@gmail.com', // Your test email
        'telepon' => '081234567890',
        'tipe_akses' => 'pemerintah',
        'instansi' => 'Dinas Ketahanan Pangan Test',
        'jabatan' => 'Staff Testing',
        'provinsi_id' => 11, // Aceh
        'kabupaten_id' => 1101, // Example kabupaten
        'jenis_dinas' => 'Ketahanan Pangan',
        'status' => 'pending',
        'catatan' => 'Test registration untuk cek email approval',
    ]);
    
    echo "✅ Test registration created successfully!\n\n";
    echo "Details:\n";
    echo "- ID: {$registrasi->id}\n";
    echo "- Name: {$registrasi->nama_lengkap}\n";
    echo "- Email: {$registrasi->email}\n";
    echo "- Type: {$registrasi->tipe_akses}\n";
    echo "- Status: {$registrasi->status}\n\n";
    
    echo "Next steps:\n";
    echo "1. Open admin panel: http://localhost:8000/admin/konsumsi-pangan/registrasi-akses\n";
    echo "2. Click 'Approve' on the test registration\n";
    echo "3. Add optional catatan if needed\n";
    echo "4. Check email inbox at: {$registrasi->email}\n";
    echo "5. Monitor queue: docker-compose logs -f queue\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating registration!\n";
    echo $e->getMessage() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

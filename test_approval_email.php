<?php

require __DIR__ . '/vendor/autoload.php';

use App\Mail\RegistrasiApprovedMail;
use App\Models\RegistrasiAkses;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Registrasi Approval Email with Queue...\n";
echo "================================================\n\n";

try {
    // Create a dummy registrasi object for testing
    $testRegistrasi = new RegistrasiAkses([
        'nama_lengkap' => 'Test User',
        'email' => 'jehianathayata@gmail.com',
        'jenis_akses' => 'pemerintah',
        'status' => 'approved',
    ]);
    
    $testPassword = 'SIKOLBIA1234';
    
    echo "Sending approval email to: {$testRegistrasi->email}\n";
    echo "This will be queued and processed by queue worker...\n\n";
    
    // Send email (will be queued because RegistrasiApprovedMail implements ShouldQueue)
    Mail::to($testRegistrasi->email)->send(new RegistrasiApprovedMail($testRegistrasi, $testPassword));
    
    echo "✓ Email job has been queued!\n\n";
    
    // Check queue
    $jobsCount = DB::table('jobs')->count();
    echo "Jobs in queue: $jobsCount\n\n";
    
    if ($jobsCount > 0) {
        echo "Queue worker should process this email shortly.\n";
        echo "Watch the queue logs: docker-compose logs -f queue\n";
    } else {
        echo "✓ Email might have been processed immediately!\n";
        echo "Check your inbox at: {$testRegistrasi->email}\n";
    }
    
} catch (\Exception $e) {
    echo "\n✗ Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

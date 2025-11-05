<?php

require __DIR__ . '/vendor/autoload.php';

use App\Mail\RegistrasiApprovedMail;
use App\Models\RegistrasiAkses;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Registrasi Approval Email with Real Data...\n";
echo "===================================================\n\n";

try {
    // Check if there's any registrasi in database
    $registrasiCount = RegistrasiAkses::count();
    echo "Total registrations in database: $registrasiCount\n\n";
    
    if ($registrasiCount === 0) {
        echo "❌ No registrations found in database.\n";
        echo "Please create a registration first through the web interface.\n";
        exit(1);
    }
    
    // Get latest pending registrasi
    $registrasi = RegistrasiAkses::where('status', 'pending')->latest()->first();
    
    if (!$registrasi) {
        // If no pending, get any registrasi for testing
        $registrasi = RegistrasiAkses::latest()->first();
        echo "⚠️  No pending registrations found. Using latest registrasi for test.\n\n";
    }
    
    echo "Using registrasi:\n";
    echo "- ID: {$registrasi->id}\n";
    echo "- Name: {$registrasi->nama_lengkap}\n";
    echo "- Email: {$registrasi->email}\n";
    echo "- Status: {$registrasi->status}\n";
    echo "- Type: {$registrasi->tipe_akses}\n\n";
    
    $testPassword = 'SIKOLBIA' . rand(1000, 9999);
    
    echo "Sending approval email...\n";
    echo "This will be queued and processed by queue worker.\n\n";
    
    // Send email (will be queued)
    Mail::to($registrasi->email)->send(new RegistrasiApprovedMail($registrasi, $testPassword));
    
    echo "✓ Email job has been queued!\n\n";
    
    // Wait a bit for queue to process
    echo "Waiting 3 seconds for queue worker to process...\n";
    sleep(3);
    
    // Check queue status
    $jobsCount = DB::table('jobs')->count();
    $failedCount = DB::table('failed_jobs')->count();
    
    echo "\nQueue Status:\n";
    echo "- Pending jobs: $jobsCount\n";
    echo "- Failed jobs: $failedCount\n\n";
    
    if ($failedCount > 0) {
        echo "❌ Email failed to send. Check logs:\n";
        echo "   docker-compose logs queue\n\n";
        
        $failed = DB::table('failed_jobs')->latest('id')->first();
        if ($failed) {
            echo "Last error:\n";
            $exception = $failed->exception;
            // Show first 500 chars of exception
            echo substr($exception, 0, 500) . "...\n";
        }
    } else if ($jobsCount === 0) {
        echo "✅ Email processed successfully!\n";
        echo "Check your inbox at: {$registrasi->email}\n";
        echo "\nTo view queue logs:\n";
        echo "   docker-compose logs -f queue\n";
    } else {
        echo "⏳ Email still in queue. Queue worker will process it soon.\n";
        echo "Watch logs: docker-compose logs -f queue\n";
    }
    
} catch (\Exception $e) {
    echo "\n❌ Error!\n";
    echo $e->getMessage() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

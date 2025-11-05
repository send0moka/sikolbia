<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing SMTP Configuration...\n";
echo "============================\n\n";

echo "Mail Configuration:\n";
echo "Mailer: " . config('mail.default') . "\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Username: " . config('mail.mailers.smtp.username') . "\n";
echo "Encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "From Address: " . config('mail.from.address') . "\n\n";

try {
    echo "Attempting to send test email...\n";
    
    Mail::raw('This is a test email from SIKOLBIA to verify SMTP configuration.', function ($message) {
        $message->to(config('mail.from.address'))
                ->subject('SIKOLBIA SMTP Test - ' . date('Y-m-d H:i:s'));
    });
    
    echo "\n✓ Email sent successfully!\n";
    echo "Please check your inbox at: " . config('mail.from.address') . "\n";
    
} catch (\Exception $e) {
    echo "\n✗ Failed to send email!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Test email dari SIKOLBIA - Setup berhasil!', function($message) {
        $message->to('jehianbusiness@gmail.com')
                ->subject('Test Email SIKOLBIA');
    });
    
    echo "✅ Email berhasil dikirim ke jehianbusiness@gmail.com\n";
    echo "📧 Silakan cek inbox Gmail Anda (atau folder Spam)\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

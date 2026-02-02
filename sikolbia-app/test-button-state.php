<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Button State Logic ===\n\n";

// Get active model
$activeModel = DB::table('model_versions')->where('is_active', true)->first();

if (!$activeModel) {
    echo "❌ No active model found\n";
    exit(1);
}

echo "Active Model: {$activeModel->version}\n";
echo "Last Data Sync: " . ($activeModel->last_data_sync ?? 'NULL') . "\n\n";

// Get latest transaksi_nbm timestamp
$latestData = DB::table('transaksi_nbms')
    ->orderBy('updated_at', 'desc')
    ->first(['updated_at', 'created_at']);

if (!$latestData) {
    echo "⚠️  No transaksi_nbm data found\n";
    exit(0);
}

echo "Latest transaksi_nbm record:\n";
echo "  Created at: {$latestData->created_at}\n";
echo "  Updated at: {$latestData->updated_at}\n\n";

// Check if there are changes
$hasChanges = false;

if (!$activeModel->last_data_sync) {
    echo "ℹ️  No last_data_sync recorded\n";
    $hasChanges = true;
} else {
    $lastSync = new DateTime($activeModel->last_data_sync);
    $latestUpdate = new DateTime($latestData->updated_at);
    
    if ($latestUpdate > $lastSync) {
        echo "✅ Data changes detected!\n";
        echo "   Latest update: {$latestData->updated_at}\n";
        echo "   Last sync: {$activeModel->last_data_sync}\n";
        $hasChanges = true;
    } else {
        echo "🔒 No data changes detected\n";
        echo "   Latest update: {$latestData->updated_at}\n";
        echo "   Last sync: {$activeModel->last_data_sync}\n";
        $hasChanges = false;
    }
}

echo "\n=== Button State ===\n";
echo "Update Model button: " . ($hasChanges ? "🟢 ENABLED" : "🔴 DISABLED") . "\n";

// Count records that changed after last sync
if ($activeModel->last_data_sync) {
    $changedCount = DB::table('transaksi_nbms')
        ->where(function($query) use ($activeModel) {
            $query->where('created_at', '>', $activeModel->last_data_sync)
                  ->orWhere('updated_at', '>', $activeModel->last_data_sync);
        })
        ->count();
    
    echo "Records changed since last sync: {$changedCount}\n";
}

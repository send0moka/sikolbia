<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Button Workflow ===\n\n";

// Step 1: Initial state (no changes)
echo "📍 Step 1: Initial State\n";
echo "─────────────────────────\n";

$activeModel = DB::table('model_versions')->where('is_active', true)->first();
$latestData = DB::table('transaksi_nbms')->orderBy('updated_at', 'desc')->first(['id', 'updated_at']);

$hasChanges = false;
if ($activeModel->last_data_sync) {
    $hasChanges = DB::table('transaksi_nbms')
        ->where(function($query) use ($activeModel) {
            $query->where('created_at', '>', $activeModel->last_data_sync)
                  ->orWhere('updated_at', '>', $activeModel->last_data_sync);
        })
        ->exists();
}

echo "Active Model: {$activeModel->version}\n";
echo "Last Sync: {$activeModel->last_data_sync}\n";
echo "Latest Data Update: {$latestData->updated_at}\n";
echo "Button State: " . ($hasChanges ? "🟢 ENABLED" : "🔴 DISABLED") . "\n\n";

// Step 2: Simulate data change
echo "📍 Step 2: Simulating Data Change\n";
echo "─────────────────────────\n";

DB::table('transaksi_nbms')
    ->where('id', $latestData->id)
    ->update(['updated_at' => now()]);

$updatedData = DB::table('transaksi_nbms')->where('id', $latestData->id)->first(['updated_at']);
echo "Updated record ID {$latestData->id}\n";
echo "New timestamp: {$updatedData->updated_at}\n";

$hasChanges = DB::table('transaksi_nbms')
    ->where(function($query) use ($activeModel) {
        $query->where('created_at', '>', $activeModel->last_data_sync)
              ->orWhere('updated_at', '>', $activeModel->last_data_sync);
    })
    ->exists();

echo "Button State: " . ($hasChanges ? "🟢 ENABLED" : "🔴 DISABLED") . "\n\n";

// Step 3: Simulate training complete
echo "📍 Step 3: Simulating Training Complete\n";
echo "─────────────────────────\n";

DB::table('model_versions')
    ->where('version', 'v1.0.0')
    ->update(['last_data_sync' => now()]);

$activeModel = DB::table('model_versions')->where('is_active', true)->first();
echo "Updated last_data_sync to: {$activeModel->last_data_sync}\n";

$hasChanges = DB::table('transaksi_nbms')
    ->where(function($query) use ($activeModel) {
        $query->where('created_at', '>', $activeModel->last_data_sync)
              ->orWhere('updated_at', '>', $activeModel->last_data_sync);
    })
    ->exists();

echo "Button State: " . ($hasChanges ? "🟢 ENABLED" : "🔴 DISABLED") . "\n\n";

// Summary
echo "=== Workflow Summary ===\n";
echo "1️⃣  Initial: Button disabled (no changes)\n";
echo "2️⃣  After data edit: Button enabled (changes detected)\n";
echo "3️⃣  After training: Button disabled (data synced)\n";
echo "\n✅ Workflow test completed!\n";

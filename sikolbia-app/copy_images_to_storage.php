<?php

// Simple script to copy images from public/daftar-alamat to storage/app/public/daftar-alamat

$sourceDir = __DIR__ . '/public/daftar-alamat';
$destinationDir = __DIR__ . '/storage/app/public/daftar-alamat';

echo "Source directory: $sourceDir\n";
echo "Destination directory: $destinationDir\n";

// Create destination directory if it doesn't exist
if (!is_dir($destinationDir)) {
    if (mkdir($destinationDir, 0755, true)) {
        echo "✓ Created directory: $destinationDir\n";
    } else {
        echo "✗ Failed to create directory: $destinationDir\n";
        exit(1);
    }
} else {
    echo "✓ Destination directory already exists\n";
}

// Check if source directory exists
if (!is_dir($sourceDir)) {
    echo "✗ Source directory does not exist: $sourceDir\n";
    exit(1);
}

// Get all files from source directory
$files = scandir($sourceDir);
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

$copiedCount = 0;
$skippedCount = 0;
$errorCount = 0;

echo "\n=== Copying Files ===\n";

foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }
    
    $sourceFile = $sourceDir . '/' . $file;
    $destinationFile = $destinationDir . '/' . $file;
    
    // Check if it's an image file
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($extension, $imageExtensions)) {
        echo "⚠ Skipped (not an image): $file\n";
        $skippedCount++;
        continue;
    }
    
    // Check if file already exists in destination
    if (file_exists($destinationFile)) {
        echo "⚠ Already exists: $file\n";
        $skippedCount++;
        continue;
    }
    
    // Copy file
    if (copy($sourceFile, $destinationFile)) {
        echo "✓ Copied: $file\n";
        $copiedCount++;
    } else {
        echo "✗ Failed to copy: $file\n";
        $errorCount++;
    }
}

echo "\n=== Summary ===\n";
echo "Files copied: $copiedCount\n";
echo "Files skipped: $skippedCount\n";
echo "Errors: $errorCount\n";

// List all files in destination
echo "\n=== Files in storage/app/public/daftar-alamat ===\n";
$destinationFiles = scandir($destinationDir);
foreach ($destinationFiles as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "- $file\n";
    }
}

echo "\n=== DONE ===\n";
echo "Images are now available in storage/app/public/daftar-alamat/\n";
echo "Make sure to run 'php artisan storage:link' to create the public symlink\n";
echo "Then images will be accessible via asset('storage/daftar-alamat/filename')\n";

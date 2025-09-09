<?php
// Simple file upload test
echo "PHP Upload Configuration:\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "max_input_time: " . ini_get('max_input_time') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "file_uploads: " . (ini_get('file_uploads') ? 'On' : 'Off') . "\n";

// Check if storage directory is writable
$storagePath = __DIR__ . '/storage/app/public/daftar-alamat';
echo "\nStorage directory: " . $storagePath . "\n";
echo "Directory exists: " . (is_dir($storagePath) ? 'Yes' : 'No') . "\n";
echo "Directory writable: " . (is_writable(dirname($storagePath)) ? 'Yes' : 'No') . "\n";

// Check if livewire temp directory exists
$livewireTempPath = __DIR__ . '/storage/app/livewire-tmp';
echo "\nLivewire temp directory: " . $livewireTempPath . "\n";
echo "Directory exists: " . (is_dir($livewireTempPath) ? 'Yes' : 'No') . "\n";
echo "Directory writable: " . (is_writable(dirname($livewireTempPath)) ? 'Yes' : 'No') . "\n";

// Create directories if they don't exist
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0755, true);
    echo "Created storage directory\n";
}

if (!is_dir($livewireTempPath)) {
    mkdir($livewireTempPath, 0755, true);
    echo "Created livewire temp directory\n";
}

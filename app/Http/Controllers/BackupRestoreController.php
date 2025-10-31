<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BackupRestoreController extends Controller
{
    // Daftar tabel yang akan di-backup
    private $tables = [
    'users',
    'cache',
    'jobs',
    'daftar_alamat',
    'permissions',
    'tb_kelompokbps',
    'tb_komoditibps',
    'transaksi_susenas',
    'kelompok',
    'komoditi',
    'transaksi_nbms',
    'transaksi_nbm_regional',
    'wilayah',
    'wilayah_kategori',
    'bulan',
    'benih_pupuk_data',
    'lahan_data',
    'iklimoptdpi_data',
    'backup_logs',
    'konsumsi',
    'sessions',
    'migrations',
    'failed_jobs',
    'password_reset_tokens',
    'roles',
    'model_has_roles',
    'role_has_permissions'
];


    /**
     * Tampilkan halaman backup & restore
     */
    public function index()
    {
        $logs = BackupLog::with('user')
            ->latest()
            ->paginate(20);

        $lastBackup = BackupLog::backups()
            ->successful()
            ->latest()
            ->first();

        $lastRestore = BackupLog::restores()
            ->successful()
            ->latest()
            ->first();

        // Get available backup files
        $backupFiles = $this->getAvailableBackups();

        return view('admin.backup-restore', compact('logs', 'lastBackup', 'lastRestore', 'backupFiles'));
    }

    /**
     * Proses backup database
     */
    public function backup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $startTime = Carbon::now();

        // Create log entry
        $log = BackupLog::create([
            'type' => 'backup',
            'filename' => '',
            'filepath' => '',
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_email' => auth()->user()->email,
            'status' => 'in_progress',
            'description' => $request->description,
            'tables_included' => $this->tables,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'started_at' => $startTime,
        ]);

        try {
            // Generate filename
            $filename = 'backup_konsumsi_pangan_' . Carbon::now()->format('Y-m-d_His') . '.sql';
            $filepath = storage_path('app/backups/' . $filename);

            // Ensure backup directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Log for debugging
            \Log::info('Starting backup', [
                'filename' => $filename,
                'filepath' => $filepath,
                'user' => auth()->user()->email,
            ]);

            // Get database configuration
            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');

            // Build mysqldump command
            $tablesString = implode(' ', $this->tables);

            // Get MySQL path from config or use default
            // For Windows Docker: use 'mysqldump' (available in container)
            // For Windows local: set MYSQL_DUMP_PATH in .env (e.g., "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe")
            $mysqldumpPath = env('MYSQL_DUMP_PATH', 'mysqldump');

            // For Windows, wrap path in quotes if it contains spaces
            if (PHP_OS_FAMILY === 'Windows' && str_contains($mysqldumpPath, ' ')) {
                $mysqldumpPath = '"' . $mysqldumpPath . '"';
            }

            // Build command with proper escaping
            // Add --skip-ssl to avoid SSL certificate verification issues in Docker
            $command = sprintf(
                '%s --user=%s --password=%s --host=%s --port=%s --skip-ssl --single-transaction --routines --triggers --add-drop-table %s %s > %s 2>&1',
                $mysqldumpPath,
                $dbUser,
                $dbPass,
                $dbHost,
                $dbPort,
                $dbName,
                $tablesString,
                escapeshellarg($filepath)
            );

            // Log command for debugging
            \Log::info('Executing mysqldump command', [
                'command' => $command,
                'tables' => $this->tables,
                'db_host' => $dbHost,
                'db_name' => $dbName,
            ]);

            // Execute backup and capture both stdout and stderr
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            // Log result
            \Log::info('Mysqldump result', [
                'return_code' => $returnVar,
                'output' => $output,
                'output_count' => count($output),
                'file_exists' => file_exists($filepath),
                'file_size' => file_exists($filepath) ? filesize($filepath) : 0,
            ]);

            if ($returnVar !== 0) {
                $errorMsg = 'Backup gagal (exit code: ' . $returnVar . ')';
                if (!empty($output)) {
                    $errorMsg .= ': ' . implode("\n", $output);
                } else {
                    $errorMsg .= '. Tidak ada output error. Command: ' . $command;
                }
                throw new \Exception($errorMsg);
            }

            if (!file_exists($filepath)) {
                throw new \Exception('File backup tidak terbuat. Command: ' . $command);
            }

            if (filesize($filepath) === 0) {
                throw new \Exception('File backup kosong (0 bytes)');
            }

            // Count records
            $recordsCount = $this->countRecords();

            // Update log
            $completedAt = Carbon::now();
            $log->update([
                'filename' => $filename,
                'filepath' => $filepath,
                'file_size' => filesize($filepath),
                'status' => 'success',
                'records_count' => $recordsCount,
                'completed_at' => $completedAt,
                'duration_seconds' => $completedAt->diffInSeconds($startTime),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat',
                'data' => [
                    'filename' => $filename,
                    'size' => $log->formatted_file_size,
                    'records' => $recordsCount,
                    'duration' => $log->formatted_duration,
                ]
            ]);
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => auth()->user()->email,
            ]);

            // Update log with error
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => Carbon::now(),
                'duration_seconds' => Carbon::now()->diffInSeconds($startTime),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Backup gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses restore database
     */
    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_file' => 'required|string',
            'description' => 'nullable|string|max:500',
            'confirm_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify password
        if (!password_verify($request->confirm_password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password tidak sesuai'
            ], 403);
        }

        $startTime = Carbon::now();
        $filepath = storage_path('app/backups/' . $request->backup_file);

        // Validate file exists
        if (!file_exists($filepath)) {
            return response()->json([
                'success' => false,
                'message' => 'File backup tidak ditemukan'
            ], 404);
        }

        // Create log entry
        $log = BackupLog::create([
            'type' => 'restore',
            'filename' => $request->backup_file,
            'filepath' => $filepath,
            'file_size' => filesize($filepath),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_email' => auth()->user()->email,
            'status' => 'in_progress',
            'description' => $request->description,
            'tables_included' => $this->tables,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'started_at' => $startTime,
        ]);

        try {
            // Get database configuration
            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');

            // Get MySQL path from config or use default
            // For Windows Docker: use 'mysql' (available in container)
            // For Windows local: set MYSQL_PATH in .env (e.g., "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe")
            $mysqlPath = env('MYSQL_PATH', 'mysql');

            // For Windows, wrap path in quotes if it contains spaces
            if (PHP_OS_FAMILY === 'Windows' && str_contains($mysqlPath, ' ')) {
                $mysqlPath = '"' . $mysqlPath . '"';
            }

            // Add --skip-ssl to avoid SSL certificate verification issues in Docker
            $command = sprintf(
                '%s --user=%s --password=%s --host=%s --port=%s --skip-ssl %s < %s 2>&1',
                $mysqlPath,
                $dbUser,
                $dbPass,
                $dbHost,
                $dbPort,
                $dbName,
                escapeshellarg($filepath)
            );

            // Execute restore
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Restore gagal (exit code: ' . $returnVar . '): ' . implode("\n", $output));
            }

            // Count records after restore
            $recordsCount = $this->countRecords();

            // Update log
            $completedAt = Carbon::now();
            $log->update([
                'status' => 'success',
                'records_count' => $recordsCount,
                'completed_at' => $completedAt,
                'duration_seconds' => $completedAt->diffInSeconds($startTime),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Restore berhasil dilakukan',
                'data' => [
                    'filename' => $request->backup_file,
                    'records' => $recordsCount,
                    'duration' => $log->formatted_duration,
                ]
            ]);
        } catch (\Exception $e) {
            // Update log with error
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => Carbon::now(),
                'duration_seconds' => Carbon::now()->diffInSeconds($startTime),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Restore gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download backup file
     */
    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);

        if (!file_exists($filepath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Log download activity
        BackupLog::create([
            'type' => 'backup',
            'filename' => $filename,
            'filepath' => $filepath,
            'file_size' => filesize($filepath),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_email' => auth()->user()->email,
            'status' => 'success',
            'description' => 'Download backup file',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'started_at' => Carbon::now(),
            'completed_at' => Carbon::now(),
        ]);

        return response()->download($filepath);
    }

    /**
     * Delete backup file
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filename' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $filepath = storage_path('app/backups/' . $request->filename);

        if (!file_exists($filepath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 404);
        }

        try {
            unlink($filepath);

            return response()->json([
                'success' => true,
                'message' => 'File backup berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available backup files
     */
    private function getAvailableBackups()
    {
        $backupDir = storage_path('app/backups');

        if (!file_exists($backupDir)) {
            return [];
        }

        $files = glob($backupDir . '/*.sql');
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => filesize($file),
                'formatted_size' => $this->formatBytes(filesize($file)),
                'created_at' => Carbon::createFromTimestamp(filemtime($file)),
                'formatted_date' => Carbon::createFromTimestamp(filemtime($file))->format('d M Y H:i:s'),
            ];
        }

        // Sort by date descending
        usort($backups, function ($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });

        return $backups;
    }

    /**
     * Count total records in all tables
     */
    private function countRecords()
    {
        $total = 0;
        foreach ($this->tables as $table) {
            try {
                $count = DB::table($table)->count();
                $total += $count;
            } catch (\Exception $e) {
                // Table might not exist, skip
                continue;
            }
        }
        return $total;
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

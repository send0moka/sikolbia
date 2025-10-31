<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'filename',
        'filepath',
        'file_size',
        'user_id',
        'user_name',
        'user_email',
        'status',
        'description',
        'error_message',
        'tables_included',
        'records_count',
        'ip_address',
        'user_agent',
        'started_at',
        'completed_at',
        'duration_seconds',
    ];

    protected $casts = [
        'tables_included' => 'array',
        'file_size' => 'integer',
        'records_count' => 'integer',
        'duration_seconds' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the backup/restore
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return 'N/A';
        }

        if ($this->duration_seconds < 60) {
            return $this->duration_seconds . ' detik';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return $minutes . ' menit ' . $seconds . ' detik';
    }

    /**
     * Scope untuk filter berdasarkan tipe
     */
    public function scopeBackups($query)
    {
        return $query->where('type', 'backup');
    }

    /**
     * Scope untuk filter berdasarkan tipe restore
     */
    public function scopeRestores($query)
    {
        return $query->where('type', 'restore');
    }

    /**
     * Scope untuk filter berdasarkan status success
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope untuk filter berdasarkan status failed
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope untuk mendapatkan log terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}

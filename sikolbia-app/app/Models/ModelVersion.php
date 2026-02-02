<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelVersion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'version',
        'model_name',
        'model_type',
        'folder_path',
        'description',
        'mae',
        'rmse',
        'mape',
        'r2_score',
        'training_data_count',
        'training_data_from',
        'training_data_to',
        'epochs',
        'model_config',
        'status',
        'is_active',
        'release_stage',
        'released_at',
        'trained_by',
        'notes',
        'artifacts',
    ];

    protected $casts = [
        'model_config' => 'array',
        'artifacts' => 'array',
        'is_active' => 'boolean',
        'released_at' => 'datetime',
        'training_data_from' => 'date',
        'training_data_to' => 'date',
        'mae' => 'decimal:2',
        'rmse' => 'decimal:2',
        'mape' => 'decimal:4',
        'r2_score' => 'decimal:4',
    ];

    /**
     * Get the user who trained this model
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trained_by');
    }

    /**
     * Scope to get only active model
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * Scope to get completed models
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'active']);
    }

    /**
     * Get the next patch version
     */
    public static function getNextPatchVersion(): string
    {
        $latestVersion = self::orderBy('version', 'desc')->first();
        
        if (!$latestVersion) {
            return 'v1.0.0';
        }
        
        // Parse version string (v1.0.0 -> [1, 0, 0])
        preg_match('/v(\d+)\.(\d+)\.(\d+)/', $latestVersion->version, $matches);
        
        if (count($matches) === 4) {
            $major = (int) $matches[1];
            $minor = (int) $matches[2];
            $patch = (int) $matches[3];
            
            return "v{$major}.{$minor}." . ($patch + 1);
        }
        
        return 'v1.0.0';
    }

    /**
     * Activate this model version
     */
    public function activate(): bool
    {
        // Deactivate all other models
        self::where('model_type', $this->model_type)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);
        
        // Activate this model
        $this->is_active = true;
        $this->status = 'active';
        
        return $this->save();
    }

    /**
     * Get formatted version for display
     */
    public function getFormattedVersionAttribute(): string
    {
        return $this->version;
    }

    /**
     * Get release stage badge color
     */
    public function getReleaseStageColorAttribute(): string
    {
        return match($this->release_stage) {
            'alpha' => 'gray',
            'beta' => 'blue',
            'production' => 'green',
            default => 'gray',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'training' => 'yellow',
            'completed' => 'blue',
            'active' => 'green',
            'archived' => 'gray',
            'failed' => 'red',
            default => 'gray',
        };
    }
}

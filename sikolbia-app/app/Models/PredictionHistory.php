<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PredictionHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'kode_kelompok',
        'kode_komoditi',
        'kelompok_name',
        'komoditi_name',
        'bulan_prediksi',
        'prediction_data',
        'historical_data',
        'confidence_intervals',
        'model_version',
        'notes',
        'is_bookmarked'
    ];

    protected $casts = [
        'prediction_data' => 'array',
        'historical_data' => 'array',
        'confidence_intervals' => 'array',
        'is_bookmarked' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the user that owns the prediction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk filter bookmarked
     */
    public function scopeBookmarked($query)
    {
        return $query->where('is_bookmarked', true);
    }

    /**
     * Scope untuk filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk recent predictions
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Get average prediction value
     */
    public function getAveragePredictionAttribute()
    {
        $predictions = $this->prediction_data ?? [];
        if (empty($predictions)) {
            return 0;
        }
        return array_sum($predictions) / count($predictions);
    }

    /**
     * Get prediction summary text
     */
    public function getSummaryAttribute()
    {
        return "{$this->komoditi_name} - {$this->bulan_prediksi} bulan - " . 
               $this->created_at->format('d M Y H:i');
    }
}

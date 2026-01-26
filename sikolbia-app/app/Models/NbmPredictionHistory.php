<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NbmPredictionHistory extends Model
{
    protected $fillable = [
        'user_id',
        'kelompok_kode',
        'komoditi_kode',
        'prediction_data',
        'notes',
        'is_bookmarked',
    ];

    protected $casts = [
        'prediction_data' => 'array',
        'is_bookmarked' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the prediction history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kelompok for the prediction.
     */
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_kode', 'kode');
    }

    /**
     * Get the komoditi for the prediction.
     */
    public function komoditi(): BelongsTo
    {
        return $this->belongsTo(Komoditi::class, 'komoditi_kode', 'kode_komoditi');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebijakanPangan extends Model
{
    use HasFactory;

    protected $table = 'kebijakan_pangan';

    protected $fillable = [
        'tanggal_berlaku',
        'jenis_kebijakan',
        'kode_komoditi_terdampak',
        'deskripsi',
        'dampak_prediksi',
        'magnitude',
        'durasi_bulan',
        'status',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'kode_komoditi_terdampak' => 'array',
        'magnitude' => 'decimal:2',
    ];

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_kebijakan', $jenis);
    }

    public function scopeByKomoditi($query, $kodeKomoditi)
    {
        return $query->whereJsonContains('kode_komoditi_terdampak', $kodeKomoditi);
    }

    public function scopeByDampak($query, $dampak)
    {
        return $query->where('dampak_prediksi', $dampak);
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }

    public function getJenisDisplayAttribute()
    {
        return ucfirst($this->jenis_kebijakan);
    }

    public function getDampakDisplayAttribute()
    {
        $dampakMap = [
            'positif' => 'Positif ↗️',
            'negatif' => 'Negatif ↘️',
            'netral' => 'Netral ➡️'
        ];
        return $dampakMap[$this->dampak_prediksi] ?? $this->dampak_prediksi;
    }

    // Static methods
    public static function getActiveByKomoditi($kodeKomoditi)
    {
        return static::aktif()
            ->byKomoditi($kodeKomoditi)
            ->orderBy('tanggal_berlaku', 'desc')
            ->get();
    }

    public static function getRecentPolicies($limit = 10)
    {
        return static::orderBy('tanggal_berlaku', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getPolicyImpact($kodeKomoditi, $startDate, $endDate = null)
    {
        $query = static::byKomoditi($kodeKomoditi)
            ->where('tanggal_berlaku', '>=', $startDate);
        
        if ($endDate) {
            $query->where('tanggal_berlaku', '<=', $endDate);
        }
        
        return $query->orderBy('tanggal_berlaku')->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolaMusiman extends Model
{
    use HasFactory;

    protected $table = 'pola_musiman';

    protected $fillable = [
        'kode_komoditi',
        'bulan',
        'faktor_musiman',
        'volatilitas_historis',
        'trend_5_tahun',
        'confidence_interval_lower',
        'confidence_interval_upper',
    ];

    protected $casts = [
        'faktor_musiman' => 'decimal:4',
        'volatilitas_historis' => 'decimal:4',
        'trend_5_tahun' => 'decimal:4',
        'confidence_interval_lower' => 'decimal:4',
        'confidence_interval_upper' => 'decimal:4',
        'last_updated' => 'datetime',
    ];

    // Relationships
    public function komoditi()
    {
        return $this->belongsTo(Komoditi::class, 'kode_komoditi', 'kode_komoditi');
    }

    // Scopes
    public function scopeByKomoditi($query, $kodeKomoditi)
    {
        return $query->where('kode_komoditi', $kodeKomoditi);
    }

    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    public function scopeHighVolatility($query, $threshold = 0.2)
    {
        return $query->where('volatilitas_historis', '>', $threshold);
    }

    // Accessors
    public function getNamaBulanAttribute()
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $namaBulan[$this->bulan] ?? 'Unknown';
    }

    public function getSeasonalImpactAttribute()
    {
        if ($this->faktor_musiman > 1.1) {
            return 'tinggi';
        } elseif ($this->faktor_musiman < 0.9) {
            return 'rendah';
        }
        return 'normal';
    }

    // Static methods
    public static function getSeasonalPattern($kodeKomoditi)
    {
        return static::byKomoditi($kodeKomoditi)
            ->orderBy('bulan')
            ->get();
    }

    public static function getPeakMonths($kodeKomoditi, $threshold = 1.2)
    {
        return static::byKomoditi($kodeKomoditi)
            ->where('faktor_musiman', '>=', $threshold)
            ->orderBy('faktor_musiman', 'desc')
            ->get();
    }

    public static function getLowMonths($kodeKomoditi, $threshold = 0.8)
    {
        return static::byKomoditi($kodeKomoditi)
            ->where('faktor_musiman', '<=', $threshold)
            ->orderBy('faktor_musiman', 'asc')
            ->get();
    }

    public static function updateSeasonalFactors($kodeKomoditi, array $monthlyData)
    {
        foreach ($monthlyData as $bulan => $data) {
            static::updateOrCreate(
                ['kode_komoditi' => $kodeKomoditi, 'bulan' => $bulan],
                [
                    'faktor_musiman' => $data['faktor_musiman'],
                    'volatilitas_historis' => $data['volatilitas_historis'] ?? null,
                    'trend_5_tahun' => $data['trend_5_tahun'] ?? null,
                    'confidence_interval_lower' => $data['ci_lower'] ?? null,
                    'confidence_interval_upper' => $data['ci_upper'] ?? null,
                    'last_updated' => now(),
                ]
            );
        }
    }
}

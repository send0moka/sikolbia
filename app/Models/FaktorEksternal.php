<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaktorEksternal extends Model
{
    use HasFactory;

    protected $table = 'faktor_eksternal';

    protected $fillable = [
        'tahun',
        'bulan',
        'pdb_nominal',
        'pdb_riil',
        'inflasi_umum',
        'inflasi_pangan',
        'nilai_tukar_rupiah',
        'harga_minyak_dunia',
        'indeks_harga_pangan_dunia',
        'el_nino_index',
        'iod_index',
    ];

    protected $casts = [
        'pdb_nominal' => 'decimal:2',
        'pdb_riil' => 'decimal:2',
        'inflasi_umum' => 'decimal:3',
        'inflasi_pangan' => 'decimal:3',
        'nilai_tukar_rupiah' => 'decimal:4',
        'harga_minyak_dunia' => 'decimal:2',
        'indeks_harga_pangan_dunia' => 'decimal:2',
        'el_nino_index' => 'decimal:3',
        'iod_index' => 'decimal:3',
    ];

    // Scopes
    public function scopeByPeriode($query, $tahun, $bulan = null)
    {
        $query->where('tahun', $tahun);
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        return $query;
    }

    public function scopeElNinoActive($query, $threshold = 0.5)
    {
        return $query->where('el_nino_index', '>=', $threshold);
    }

    public function scopeLaNinaActive($query, $threshold = -0.5)
    {
        return $query->where('el_nino_index', '<=', $threshold);
    }

    public function scopeHighInflation($query, $threshold = 5.0)
    {
        return $query->where('inflasi_pangan', '>=', $threshold);
    }

    // Accessors
    public function getPeriodeDisplayAttribute()
    {
        return "{$this->tahun}-" . str_pad($this->bulan, 2, '0', STR_PAD_LEFT);
    }

    public function getClimateConditionAttribute()
    {
        if ($this->el_nino_index >= 0.5) {
            return 'El Niño';
        } elseif ($this->el_nino_index <= -0.5) {
            return 'La Niña';
        }
        return 'Normal';
    }

    public function getInflationCategoryAttribute()
    {
        if ($this->inflasi_pangan >= 10) {
            return 'sangat_tinggi';
        } elseif ($this->inflasi_pangan >= 5) {
            return 'tinggi';
        } elseif ($this->inflasi_pangan >= 2) {
            return 'sedang';
        }
        return 'rendah';
    }

    // Static methods
    public static function getLatestData($limit = 12)
    {
        return static::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getByYear($tahun)
    {
        return static::where('tahun', $tahun)
            ->orderBy('bulan')
            ->get();
    }

    public static function getClimateEvents($startYear, $endYear = null)
    {
        $query = static::where('tahun', '>=', $startYear);
        if ($endYear) {
            $query->where('tahun', '<=', $endYear);
        }
        
        return $query->where(function($q) {
            $q->where('el_nino_index', '>=', 0.5)
              ->orWhere('el_nino_index', '<=', -0.5);
        })->orderBy('tahun')->orderBy('bulan')->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiNbmRegional extends Model
{
    use HasFactory;

    protected $table = 'transaksi_nbm_regional';

    protected $fillable = [
        'kode_provinsi',
        'nama_provinsi',
        'kode_kelompok',
        'kode_komoditi',
        'tahun',
        'bulan',
        'produksi_lokal',
        'konsumsi_regional',
        'surplus_defisit',
        'harga_regional',
    ];

    protected $casts = [
        'produksi_lokal' => 'decimal:4',
        'konsumsi_regional' => 'decimal:4',
        'surplus_defisit' => 'decimal:4',
        'harga_regional' => 'decimal:2',
    ];

    // Relationships
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kode_kelompok', 'kode');
    }

    public function komoditi()
    {
        return $this->belongsTo(Komoditi::class, 'kode_komoditi', 'kode_komoditi');
    }

    // Scopes
    public function scopeByProvinsi($query, $kodeProvinsi)
    {
        return $query->where('kode_provinsi', $kodeProvinsi);
    }

    public function scopeByPeriode($query, $tahun, $bulan = null)
    {
        $query->where('tahun', $tahun);
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        return $query;
    }

    public function scopeSurplus($query)
    {
        return $query->where('surplus_defisit', '>', 0);
    }

    public function scopeDefisit($query)
    {
        return $query->where('surplus_defisit', '<', 0);
    }

    // Accessors
    public function getPeriodeDisplayAttribute()
    {
        if ($this->bulan) {
            return "{$this->tahun}-" . str_pad($this->bulan, 2, '0', STR_PAD_LEFT);
        }
        return (string) $this->tahun;
    }

    public function getStatusSurplusDefisitAttribute()
    {
        if ($this->surplus_defisit > 0) {
            return 'surplus';
        } elseif ($this->surplus_defisit < 0) {
            return 'defisit';
        }
        return 'seimbang';
    }

    // Static methods
    public static function getProvinsiSurplus($kodeKomoditi, $tahun)
    {
        return static::where('kode_komoditi', $kodeKomoditi)
            ->where('tahun', $tahun)
            ->surplus()
            ->orderBy('surplus_defisit', 'desc')
            ->get();
    }

    public static function getProvinsiDefisit($kodeKomoditi, $tahun)
    {
        return static::where('kode_komoditi', $kodeKomoditi)
            ->where('tahun', $tahun)
            ->defisit()
            ->orderBy('surplus_defisit', 'asc')
            ->get();
    }
}

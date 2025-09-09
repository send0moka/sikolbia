<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiNbm extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_kelompok',
        'kode_komoditi',
        'tahun',
        'bulan',
        'kuartal',
        'periode_data',
        'status_angka',
        'masukan',
        'keluaran',
        'impor',
        'ekspor',
        'perubahan_stok',
        'pakan',
        'bibit',
        'makanan',
        'bukan_makanan',
        'tercecer',
        'penggunaan_lain',
        'bahan_makanan',
        'kg_tahun',
        'gram_hari',
        'kalori_hari',
        'protein_hari',
        'lemak_hari',
        'harga_produsen',
        'harga_konsumen',
        'inflasi_komoditi',
        'nilai_tukar_usd',
        'populasi_indonesia',
        'gdp_per_kapita',
        'tingkat_kemiskinan',
        'curah_hujan_mm',
        'suhu_rata_celsius',
        'indeks_el_nino',
        'luas_panen_ha',
        'produktivitas_ton_ha',
        'kebijakan_impor',
        'subsidi_pemerintah',
        'stok_bulog',
        'confidence_score',
        'data_source',
        'validation_status',
        'outlier_flag',
    ];

    protected $casts = [
        'masukan' => 'decimal:4',
        'keluaran' => 'decimal:4',
        'impor' => 'decimal:4',
        'ekspor' => 'decimal:4',
        'perubahan_stok' => 'decimal:4',
        'pakan' => 'decimal:4',
        'bibit' => 'decimal:4',
        'makanan' => 'decimal:4',
        'bukan_makanan' => 'decimal:4',
        'tercecer' => 'decimal:4',
        'penggunaan_lain' => 'decimal:4',
        'bahan_makanan' => 'decimal:4',
        'kg_tahun' => 'decimal:4',
        'gram_hari' => 'decimal:4',
        'kalori_hari' => 'decimal:4',
        'protein_hari' => 'decimal:4',
        'lemak_hari' => 'decimal:6',
        'harga_produsen' => 'decimal:4',
        'harga_konsumen' => 'decimal:4',
        'inflasi_komoditi' => 'decimal:4',
        'nilai_tukar_usd' => 'decimal:4',
        'gdp_per_kapita' => 'decimal:2',
        'tingkat_kemiskinan' => 'decimal:2',
        'curah_hujan_mm' => 'decimal:2',
        'suhu_rata_celsius' => 'decimal:2',
        'indeks_el_nino' => 'decimal:3',
        'luas_panen_ha' => 'decimal:2',
        'produktivitas_ton_ha' => 'decimal:4',
        'subsidi_pemerintah' => 'decimal:2',
        'stok_bulog' => 'decimal:4',
        'confidence_score' => 'decimal:2',
        'outlier_flag' => 'boolean',
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
    public function scopeByPeriode($query, $tahun, $bulan = null)
    {
        $query->where('tahun', $tahun);
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        return $query;
    }

    public function scopeVerified($query)
    {
        return $query->where('validation_status', 'verified');
    }

    public function scopeNotOutlier($query)
    {
        return $query->where('outlier_flag', false);
    }

    // Accessors
    public function getPeriodeDisplayAttribute()
    {
        if ($this->bulan) {
            return "{$this->tahun}-" . str_pad($this->bulan, 2, '0', STR_PAD_LEFT);
        }
        return (string) $this->tahun;
    }

    public function getKaloriPerKapitaAttribute()
    {
        if ($this->populasi_indonesia && $this->kalori_hari) {
            return ($this->kalori_hari * 365 * 1000000) / $this->populasi_indonesia;
        }
        return null;
    }

    // Static methods
    public static function getLatestData($kodeKomoditi, $limit = 12)
    {
        return static::where('kode_komoditi', $kodeKomoditi)
            ->verified()
            ->notOutlier()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getTimeSeriesData($kodeKomoditi, $startYear, $endYear = null)
    {
        $query = static::where('kode_komoditi', $kodeKomoditi)
            ->verified()
            ->where('tahun', '>=', $startYear);
        
        if ($endYear) {
            $query->where('tahun', '<=', $endYear);
        }
        
        return $query->orderBy('tahun')->orderBy('bulan')->get();
    }
}

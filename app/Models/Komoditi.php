<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditi extends Model
{
    use HasFactory;
    
    protected $table = 'komoditi';
    
    protected $fillable = [
        'kode_kelompok',
        'kode_komoditi',
        'nama',
        'satuan_dasar',
        'kalori_per_100g',
        'protein_per_100g',
        'lemak_per_100g',
        'karbohidrat_per_100g',
        'serat_per_100g',
        'vitamin_c_per_100g',
        'zat_besi_per_100g',
        'kalsium_per_100g',
        'musim_panen',
        'asal_produksi',
        'shelf_life_hari',
        'harga_rata_per_kg',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'kalori_per_100g' => 'decimal:2',
            'protein_per_100g' => 'decimal:2',
            'lemak_per_100g' => 'decimal:2',
            'karbohidrat_per_100g' => 'decimal:2',
            'serat_per_100g' => 'decimal:2',
            'vitamin_c_per_100g' => 'decimal:2',
            'zat_besi_per_100g' => 'decimal:2',
            'kalsium_per_100g' => 'decimal:2',
            'harga_rata_per_kg' => 'decimal:2',
        ];
    }

    // Relationships
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kode_kelompok', 'kode');
    }

    public function transaksiNbms()
    {
        return $this->hasMany(TransaksiNbm::class, 'kode_komoditi', 'kode_komoditi');
    }

    public function transaksiRegional()
    {
        return $this->hasMany(TransaksiNbmRegional::class, 'kode_komoditi', 'kode_komoditi');
    }

    public function polaMusiman()
    {
        return $this->hasMany(PolaMusiman::class, 'kode_komoditi', 'kode_komoditi');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->nama . " ({$this->kode_komoditi})";
    }

    public function getNutrisiLengkapAttribute()
    {
        return [
            'kalori' => $this->kalori_per_100g,
            'protein' => $this->protein_per_100g,
            'lemak' => $this->lemak_per_100g,
            'karbohidrat' => $this->karbohidrat_per_100g,
            'serat' => $this->serat_per_100g,
            'vitamin_c' => $this->vitamin_c_per_100g,
            'zat_besi' => $this->zat_besi_per_100g,
            'kalsium' => $this->kalsium_per_100g,
        ];
    }

    // Scopes
    public function scopeByKelompok($query, $kodeKelompok)
    {
        return $query->where('kode_kelompok', $kodeKelompok);
    }

    public function scopeLokal($query)
    {
        return $query->where('asal_produksi', 'lokal');
    }

    public function scopeMusimPanen($query, $musim)
    {
        return $query->where('musim_panen', $musim);
    }

    // Static methods
    public static function getDropdownOptions()
    {
        return static::orderBy('nama')->pluck('nama', 'kode_komoditi')->toArray();
    }

    public static function getByKelompok($kodeKelompok)
    {
        return static::byKelompok($kodeKelompok)->orderBy('nama')->get();
    }

    public static function getKomoditiLokal()
    {
        return static::lokal()->with('kelompok')->orderBy('nama')->get();
    }
}

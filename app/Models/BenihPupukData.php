<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenihPupukData extends Model
{
    use HasFactory;

    protected $table = 'benih_pupuk_data';
    
    public $timestamps = false;

    protected $fillable = [
        'tahun',
        'id_bulan',
        'id_wilayah',
        'id_variabel',
        'id_klasifikasi',
        'nilai',
        'status',
        'date_created',
        'date_modified',
    ];

    protected $casts = [
        'id' => 'integer',
        'tahun' => 'integer',
        'id_bulan' => 'integer',
        'id_wilayah' => 'integer',
        'id_variabel' => 'integer',
        'id_klasifikasi' => 'integer',
        'nilai' => 'float',
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
    ];

    // Relationships
    public function bulan()
    {
        return $this->belongsTo(BenihPupukBulan::class, 'id_bulan');
    }

    public function wilayah()
    {
        return $this->belongsTo(BenihPupukWilayah::class, 'id_wilayah');
    }

    public function variabel()
    {
        return $this->belongsTo(BenihPupukVariabel::class, 'id_variabel');
    }

    public function klasifikasi()
    {
        return $this->belongsTo(BenihPupukKlasifikasi::class, 'id_klasifikasi');
    }

    // Scopes
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    public function scopeByBulan($query, $bulan)
    {
        return $query->where('id_bulan', $bulan);
    }

    public function scopeByWilayah($query, $wilayahId)
    {
        return $query->where('id_wilayah', $wilayahId);
    }

    public function scopeByVariabel($query, $variabelId)
    {
        return $query->where('id_variabel', $variabelId);
    }

    public function scopeByKlasifikasi($query, $klasifikasiId)
    {
        return $query->where('id_klasifikasi', $klasifikasiId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'A');
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['bulan', 'wilayah', 'variabel', 'klasifikasi']);
    }

    // Accessors
    public function getFormattedNilaiAttribute()
    {
        return $this->nilai ? number_format($this->nilai, 2, ',', '.') : '-';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'A' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
            'I' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
            'D' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
            default => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-900/20 dark:text-neutral-400'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'A' => 'Aktif',
            'I' => 'Tidak Aktif',
            'D' => 'Draft',
            default => 'Unknown'
        };
    }

    // Static methods
    public static function getAvailableYears()
    {
        return static::distinct('tahun')->orderByDesc('tahun')->pluck('tahun')->toArray();
    }

    public static function getStatusOptions()
    {
        return [
            'A' => 'Aktif',
            'I' => 'Tidak Aktif',
            'D' => 'Draft',
        ];
    }

    public static function getDataByFilters($filters = [])
    {
        $query = static::withRelations();

        if (!empty($filters['tahun'])) {
            $query->byTahun($filters['tahun']);
        }

        if (!empty($filters['bulan'])) {
            $query->byBulan($filters['bulan']);
        }

        if (!empty($filters['wilayah'])) {
            $query->byWilayah($filters['wilayah']);
        }

        if (!empty($filters['variabel'])) {
            $query->byVariabel($filters['variabel']);
        }

        if (!empty($filters['klasifikasi'])) {
            $query->byKlasifikasi($filters['klasifikasi']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('tahun')
                    ->orderBy('id_bulan')
                    ->orderBy('id_wilayah')
                    ->orderBy('id_variabel');
    }
}

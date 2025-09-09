<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;
    
    protected $table = 'kelompok';
    
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'prioritas_nasional',
        'target_konsumsi_harian',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'status_aktif' => 'boolean',
            'target_konsumsi_harian' => 'decimal:2',
        ];
    }

    // Relationships
    public function komoditi()
    {
        return $this->hasMany(Komoditi::class, 'kode_kelompok', 'kode');
    }

    public function transaksiNbms()
    {
        return $this->hasMany(TransaksiNbm::class, 'kode_kelompok', 'kode');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->nama . ($this->deskripsi ? " - {$this->deskripsi}" : '');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopePrioritas($query, $prioritas)
    {
        return $query->where('prioritas_nasional', $prioritas);
    }

    // Static methods
    public static function getDropdownOptions()
    {
        return static::aktif()->orderBy('nama')->pluck('nama', 'kode')->toArray();
    }

    public static function getPrioritasTinggi()
    {
        return static::aktif()->prioritas('tinggi')->orderBy('nama')->get();
    }
}

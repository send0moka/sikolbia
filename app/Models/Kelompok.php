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
        'ake_ketersediaan',
        'skor_pph',
        'status_aktif',
        'icon_class',
        'color_class',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'status_aktif' => 'boolean',
            'ake_ketersediaan' => 'decimal:2',
            'skor_pph' => 'decimal:2',
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

    // Static methods
    public static function getDropdownOptions()
    {
        return static::aktif()->orderBy('nama')->pluck('nama', 'kode')->toArray();
    }
}

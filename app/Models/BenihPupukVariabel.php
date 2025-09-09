<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenihPupukVariabel extends Model
{
    use HasFactory;

    protected $table = 'benih_pupuk_variabel';
    
    public $timestamps = false;

    protected $fillable = [
        'id_topik',
        'deskripsi',
        'satuan',
        'sorter',
    ];

    protected $casts = [
        'id' => 'integer',
        'id_topik' => 'integer',
        'sorter' => 'integer',
    ];

    // Relationships
    public function topik()
    {
        return $this->belongsTo(BenihPupukTopik::class, 'id_topik');
    }

    public function klasifikasis()
    {
        return $this->belongsToMany(BenihPupukKlasifikasi::class, 'benih_pupuk_variabel_klasifikasi', 'id_variabel', 'id_klasifikasi');
    }

    public function data()
    {
        return $this->hasMany(BenihPupukData::class, 'id_variabel');
    }

    // Scopes
    public function scopeByTopik($query, $topikId)
    {
        return $query->where('id_topik', $topikId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sorter')->orderBy('deskripsi');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->deskripsi ?: "Variabel #{$this->id}";
    }

    public function getFullDescriptionAttribute()
    {
        $desc = $this->deskripsi;
        if ($this->satuan) {
            $desc .= " ({$this->satuan})";
        }
        return $desc;
    }

    // Static methods
    public static function getByTopik($topikId)
    {
        return static::byTopik($topikId)->ordered()->get();
    }

    public static function getDropdownOptions($topikId = null)
    {
        $query = static::ordered();
        if ($topikId) {
            $query->byTopik($topikId);
        }
        return $query->pluck('deskripsi', 'id')->toArray();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenihPupukWilayah extends Model
{
    use HasFactory;

    protected $table = 'benih_pupuk_wilayah';
    
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama',
        'id_kategori',
        'id_parent',
        'sorter',
    ];

    protected $casts = [
        'id' => 'integer',
        'kode' => 'integer',
        'id_kategori' => 'integer',
        'id_parent' => 'integer',
        'sorter' => 'integer',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(BenihPupukWilayahKategori::class, 'id_kategori');
    }

    public function parent()
    {
        return $this->belongsTo(BenihPupukWilayah::class, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany(BenihPupukWilayah::class, 'id_parent');
    }

    public function data()
    {
        return $this->hasMany(BenihPupukData::class, 'id_wilayah');
    }

    // Scopes
    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('id_kategori', $kategoriId);
    }

    public function scopeProvinsi($query)
    {
        return $query->whereNull('id_parent')->orWhere('id_parent', 0);
    }

    public function scopeKabupaten($query, $provinsiId = null)
    {
        $query = $query->whereNotNull('id_parent')->where('id_parent', '>', 0);
        if ($provinsiId) {
            $query->where('id_parent', $provinsiId);
        }
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sorter')->orderBy('nama');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->nama ?: "Wilayah #{$this->id}";
    }

    public function getFullNameAttribute()
    {
        $name = $this->nama;
        if ($this->parent) {
            $name = $this->parent->nama . ' - ' . $name;
        }
        return $name;
    }

    public function getIsProvinsiAttribute()
    {
        return is_null($this->id_parent) || $this->id_parent == 0;
    }

    // Static methods
    public static function getProvinsi()
    {
        return static::provinsi()->ordered()->get();
    }

    public static function getKabupaten($provinsiId = null)
    {
        return static::kabupaten($provinsiId)->ordered()->get();
    }

    public static function getDropdownOptions($kategoriId = null, $parentId = null)
    {
        $query = static::ordered();
        
        if ($kategoriId) {
            $query->byKategori($kategoriId);
        }
        
        if ($parentId) {
            $query->where('id_parent', $parentId);
        } elseif ($parentId === 0) {
            $query->provinsi();
        }
        
        return $query->pluck('nama', 'id')->toArray();
    }
}

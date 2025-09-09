<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenihPupukWilayahKategori extends Model
{
    use HasFactory;

    protected $table = 'benih_pupuk_wilayah_kategori';
    
    public $timestamps = false;

    protected $fillable = [
        'deskripsi',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    // Relationships
    public function wilayahs()
    {
        return $this->hasMany(BenihPupukWilayah::class, 'id_kategori');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->deskripsi ?: "Kategori #{$this->id}";
    }

    // Static methods
    public static function getDropdownOptions()
    {
        return static::orderBy('deskripsi')->pluck('deskripsi', 'id')->toArray();
    }
}

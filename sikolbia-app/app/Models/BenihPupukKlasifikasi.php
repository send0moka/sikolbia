<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenihPupukKlasifikasi extends Model
{
    use HasFactory;

    protected $table = 'benih_pupuk_klasifikasi';
    
    public $timestamps = false;

    protected $fillable = [
        'deskripsi',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    // Relationships
    public function variabels()
    {
        return $this->belongsToMany(BenihPupukVariabel::class, 'benih_pupuk_variabel_klasifikasi', 'id_klasifikasi', 'id_variabel');
    }

    public function data()
    {
        return $this->hasMany(BenihPupukData::class, 'id_klasifikasi');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->deskripsi ?: "Klasifikasi #{$this->id}";
    }

    // Static methods
    public static function getDropdownOptions()
    {
        return static::orderBy('deskripsi')->pluck('deskripsi', 'id')->toArray();
    }

    public static function getByVariabels($variabelIds)
    {
        return static::whereHas('variabels', function ($query) use ($variabelIds) {
            $query->whereIn('id_variabel', $variabelIds);
        })->orderBy('deskripsi')->get();
    }
}

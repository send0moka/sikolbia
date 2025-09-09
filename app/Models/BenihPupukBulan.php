<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenihPupukBulan extends Model
{
    use HasFactory;

    protected $table = 'benih_pupuk_bulan';
    
    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    // Relationships
    public function data()
    {
        return $this->hasMany(BenihPupukData::class, 'id_bulan');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->nama ?: "Bulan #{$this->id}";
    }

    // Static methods
    public static function getDropdownOptions()
    {
        return static::orderBy('id')->pluck('nama', 'id')->toArray();
    }

    public static function getBulanNames()
    {
        return [
            1 => 'Januari',
            2 => 'Februari', 
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bulan extends Model
{
    protected $table = 'bulan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'nama'
    ];
    
    protected $casts = [
        'id' => 'integer'
    ];
    
    /**
     * Get the data records for this bulan.
     */
    // public function data(): HasMany
    // {
    //     return $this->hasMany(Data::class, 'id_bulan', 'id');
    // }

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
            12 => 'Desember',
            13 => 'Setahun'
        ];
    }
}

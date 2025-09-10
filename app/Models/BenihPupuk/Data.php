<?php

namespace App\Models\BenihPupuk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BenihPupuk\Variabel;
use App\Models\BenihPupuk\Klasifikasi;
use App\Models\BenihPupuk\Topik;
use App\Models\Wilayah;
use App\Models\Bulan;

class Data extends Model
{
    protected $table = 'benih_pupuk_data';
    public $incrementing = true;
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'tahun',
        'id_bulan',
        'id_wilayah',
        'id_variabel',
        'id_klasifikasi',
        'nilai',
        'status',
    ];
    
    protected $casts = [
        'tahun' => 'integer',
        'id_bulan' => 'integer',
        'id_wilayah' => 'integer',
        'id_variabel' => 'integer',
        'id_klasifikasi' => 'integer',
        'nilai' => 'float',
    ];
    
    /**
     * Get the bulan for this data.
     */
    public function bulan(): BelongsTo
    {
        return $this->belongsTo(Bulan::class, 'id_bulan', 'id');
    }
    
    /**
     * Get the wilayah for this data.
     */
    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id');
    }
    
    /**
     * Get the variabel for this data.
     */
    public function variabel(): BelongsTo
    {
        return $this->belongsTo(Variabel::class, 'id_variabel', 'id');
    }
    
    /**
     * Get the klasifikasi for this data.
     */
    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(Klasifikasi::class, 'id_klasifikasi', 'id');
    }
}

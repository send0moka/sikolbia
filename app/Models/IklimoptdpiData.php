<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Bulan;
use App\Models\Wilayah;
use App\Models\IklimoptdpiVariabel;
use App\Models\IklimoptdpiKlasifikasi;

class IklimoptdpiData extends Model
{
    use HasFactory;
    
    protected $table = 'iklimoptdpi_data';
    
    // Composite primary key
    protected $primaryKey = null;
    public $incrementing = false;
    
    protected $fillable = [
        'tahun',
        'id_bulan',
        'id_variabel',
        'id_klasifikasi',
        'id_wilayah',
        'nilai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'id_bulan' => 'integer',
            'nilai' => 'double',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function bulan(): BelongsTo
    {
        return $this->belongsTo(Bulan::class, 'id_bulan');
    }

    public function variabel(): BelongsTo
    {
        return $this->belongsTo(IklimoptdpiVariabel::class, 'id_variabel');
    }

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(IklimoptdpiKlasifikasi::class, 'id_klasifikasi');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }
}

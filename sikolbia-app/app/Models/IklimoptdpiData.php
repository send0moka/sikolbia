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
    
    // Primary key
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    
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

    // Get the topik through variabel relationship (no direct id_topik on data table)
    public function topik()
    {
        return $this->hasOneThrough(
            IklimoptdpiTopik::class,
            IklimoptdpiVariabel::class,
            'id', // Foreign key on iklimoptdpi_variabel table
            'id', // Foreign key on iklimoptdpi_topik table
            'id_variabel', // Local key on iklimoptdpi_data table
            'id_topik' // Local key on iklimoptdpi_variabel table
        );
    }

    // Aliases used elsewhere in the codebase (keep for backward compatibility)
    public function iklimoptdpiTopik()
    {
        return $this->topik();
    }

    public function iklimoptdpiVariabel(): BelongsTo
    {
        return $this->variabel();
    }

    public function iklimoptdpiKlasifikasi(): BelongsTo
    {
        return $this->klasifikasi();
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

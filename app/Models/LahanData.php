<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class LahanData extends Model
{
    use HasFactory;
    
    protected $table = 'lahan_data';
    
    protected $fillable = [
        'tahun',
        'id_bulan',
        'id_wilayah',
        'id_variabel',
        'id_klasifikasi',
        'nilai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:4',
            'tahun' => 'integer',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function bulan(): BelongsTo
    {
        return $this->belongsTo(Bulan::class, 'id_bulan');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }

    public function variabel(): BelongsTo
    {
        return $this->belongsTo(LahanVariabel::class, 'id_variabel');
    }

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(LahanKlasifikasi::class, 'id_klasifikasi');
    }

    // Get the topik through variabel relationship
    public function topik()
    {
        return $this->hasOneThrough(
            LahanTopik::class,
            LahanVariabel::class,
            'id', // Foreign key on lahan_variabel table
            'id', // Foreign key on lahan_topik table  
            'id_variabel', // Local key on lahan_data table
            'id_topik' // Local key on lahan_variabel table
        );
    }

    // Alias for topik relationship (for backward compatibility)
    public function lahanTopik()
    {
        return $this->topik();
    }
}

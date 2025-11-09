<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumsi extends Model
{
    use HasFactory;

    protected $table = 'konsumsi';

    protected $fillable = [
        'kode_komoditi',
        'tahun',
        'bulan',
        'konsumsi_per_kapita',
        'konsumsi_total',
        'jumlah_penduduk',
        'keterangan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'konsumsi_per_kapita' => 'decimal:4',
        'konsumsi_total' => 'decimal:4',
        'jumlah_penduduk' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

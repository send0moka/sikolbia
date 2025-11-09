<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiSusenas extends Model
{
    protected $table = 'transaksi_susenas';

    protected $fillable = [
        'kd_kelompokbps',
        'kd_komoditibps',
        'tahun',
        'Satuan',
        'konsumsikuantity',
        'konsumsinilai',
        'konsumsigizi',
    ];

    protected $casts = [
        'konsumsikuantity' => 'decimal:2',
        'konsumsinilai' => 'decimal:2',
        'konsumsigizi' => 'decimal:2',
        'tahun' => 'integer',
    ];

    /**
     * Get the kelompokbps that owns the transaksi susenas.
     */
    public function kelompokbps(): BelongsTo
    {
        return $this->belongsTo(TbKelompokbps::class, 'kd_kelompokbps', 'kd_kelompokbps');
    }

    /**
     * Get the komoditibps that owns the transaksi susenas.
     */
    public function komoditibps(): BelongsTo
    {
        return $this->belongsTo(TbKomoditibps::class, 'kd_komoditibps', 'kd_komoditibps');
    }
}

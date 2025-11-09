<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TbKomoditibps extends Model
{
    protected $table = 'tb_komoditibps';
    protected $primaryKey = 'kd_komoditibps';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_komoditibps',
        'nm_komoditibps',
        'kd_kelompokbps',
    ];

    /**
     * Get the kelompokbps that owns the komoditibps.
     */
    public function kelompokbps(): BelongsTo
    {
        return $this->belongsTo(TbKelompokbps::class, 'kd_kelompokbps', 'kd_kelompokbps');
    }

    /**
     * Get the transaksi susenas for the komoditibps.
     */
    public function transaksiSusenas(): HasMany
    {
        return $this->hasMany(TransaksiSusenas::class, 'kd_komoditibps', 'kd_komoditibps');
    }
}

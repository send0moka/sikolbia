<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TbKelompokbps extends Model
{
    protected $table = 'tb_kelompokbps';
    protected $primaryKey = 'kd_kelompokbps';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_kelompokbps',
        'nm_kelompokbps',
    ];

    /**
     * Get the komoditibps for the kelompokbps.
     */
    public function komoditibps(): HasMany
    {
        return $this->hasMany(TbKomoditibps::class, 'kd_kelompokbps', 'kd_kelompokbps');
    }

    /**
     * Get the transaksi susenas for the kelompokbps.
     */
    public function transaksiSusenas(): HasMany
    {
        return $this->hasMany(TransaksiSusenas::class, 'kd_kelompokbps', 'kd_kelompokbps');
    }
}

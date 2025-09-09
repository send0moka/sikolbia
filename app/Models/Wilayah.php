<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    public $timestamps = false;

    protected $fillable = [
        'id_kategori',
        'id_parent',
        'kode',
        'nama',
        'sorter'
    ];

    protected $casts = [
        'id' => 'integer',
        'id_kategori' => 'integer',
        'id_parent' => 'integer',
        'sorter' => 'integer'
    ];

    /**
     * Get the kategori for this wilayah.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(WilayahKategori::class, 'id_kategori');
    }

    /**
     * Get the lahan data for this wilayah.
     */
    public function lahanData(): HasMany
    {
        return $this->hasMany(LahanData::class, 'id_wilayah');
    }
}

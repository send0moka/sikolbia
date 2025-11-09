<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WilayahKategori extends Model
{
    protected $table = 'wilayah_kategori';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'deskripsi'
    ];
    
    /**
     * Get the wilayah records for this kategori.
     */
    public function wilayahs(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'id_kategori', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LahanVariabel extends Model
{
    use HasFactory;
    
    protected $table = 'lahan_variabel';
    public $timestamps = false;
    
    protected $fillable = [
        'id_topik',
        'deskripsi',
        'satuan',
        'sorter',
    ];

    public function topik(): BelongsTo
    {
        return $this->belongsTo(LahanTopik::class, 'id_topik');
    }

    public function klasifikasis(): HasMany
    {
        return $this->hasMany(LahanKlasifikasi::class, 'id_variabel');
    }

    public function data(): HasMany
    {
        return $this->hasMany(LahanData::class, 'id_variabel');
    }
}

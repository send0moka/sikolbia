<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LahanKlasifikasi extends Model
{
    use HasFactory;
    
    protected $table = 'lahan_klasifikasi';
    public $timestamps = false;
    
    protected $fillable = [
        'id_variabel',
        'deskripsi',
        'sorter',
    ];

    public function variabel(): BelongsTo
    {
        return $this->belongsTo(LahanVariabel::class, 'id_variabel');
    }

    public function data(): HasMany
    {
        return $this->hasMany(LahanData::class, 'id_klasifikasi');
    }
}

<?php

namespace App\Models\BenihPupuk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Klasifikasi extends Model
{
    protected $table = 'benih_pupuk_klasifikasi';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'id_variabel',
        'deskripsi'
    ];
    
    /**
     * The variabel that this klasifikasi belongs to.
     */
    public function variabel(): BelongsTo
    {
        return $this->belongsTo(Variabel::class, 'id_variabel', 'id');
    }
    
    /**
     * Get the data records for this klasifikasi.
     */
    public function data(): HasMany
    {
        return $this->hasMany(Data::class, 'id_klasifikasi', 'id');
    }
}

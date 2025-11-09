<?php

namespace App\Models\BenihPupuk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topik extends Model
{
    protected $table = 'benih_pupuk_topik';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'deskripsi'
    ];
    
    /**
     * Get the variabel records for this topik.
     */
    public function variabels(): HasMany
    {
        return $this->hasMany(Variabel::class, 'id_topik', 'id');
    }
}

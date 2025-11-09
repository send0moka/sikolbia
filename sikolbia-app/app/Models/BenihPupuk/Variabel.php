<?php

namespace App\Models\BenihPupuk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variabel extends Model
{
    protected $table = 'benih_pupuk_variabel';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'id_topik',
        'deskripsi',
        'satuan',
        'sorter'
    ];
    
    protected $casts = [
        'id_topik' => 'integer',
        'sorter' => 'integer'
    ];
    
    /**
     * Get the topik for this variabel.
     */
    public function topik(): BelongsTo
    {
        return $this->belongsTo(Topik::class, 'id_topik', 'id');
    }
    
    /**
     * Klasifikasis under this variabel (direct FK, no pivot).
     */
    public function klasifikasis(): HasMany
    {
        return $this->hasMany(Klasifikasi::class, 'id_variabel', 'id');
    }
    
    /**
     * Get the data records for this variabel.
     */
    public function data(): HasMany
    {
        return $this->hasMany(Data::class, 'id_variabel', 'id');
    }
}

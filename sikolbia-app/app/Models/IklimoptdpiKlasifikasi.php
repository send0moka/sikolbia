<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IklimoptdpiKlasifikasi extends Model
{
    use HasFactory;
    
    protected $table = 'iklimoptdpi_klasifikasi';
    
    protected $fillable = [
        'id_variabel',
        'deskripsi',
        'nilai_min',
        'nilai_max',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function variabel()
    {
        return $this->belongsTo(IklimoptdpiVariabel::class, 'id_variabel');
    }

    public function iklimoptdpiData(): HasMany
    {
        return $this->hasMany(IklimoptdpiData::class, 'id_klasifikasi');
    }
}

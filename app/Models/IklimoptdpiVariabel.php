<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IklimoptdpiVariabel extends Model
{
    use HasFactory;
    
    protected $table = 'iklimoptdpi_variabel';
    
    protected $fillable = [
        'id_topik',
        'deskripsi',
        'satuan',
        'sorter',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function topik(): BelongsTo
    {
        return $this->belongsTo(IklimoptdpiTopik::class, 'id_topik');
    }

    public function iklimoptdpiData(): HasMany
    {
        return $this->hasMany(IklimoptdpiData::class, 'id_variabel');
    }
}

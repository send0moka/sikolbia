<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IklimoptdpiTopik extends Model
{
    use HasFactory;
    
    protected $table = 'iklimoptdpi_topik';
    
    protected $fillable = [
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function variabels(): HasMany
    {
        return $this->hasMany(IklimoptdpiVariabel::class, 'id_topik');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class LahanTopik extends Model
{
    use HasFactory;
    
    protected $table = 'lahan_topik';
    public $timestamps = false;
    
    protected $fillable = [
        'deskripsi',
    ];

    public function variabels(): HasMany
    {
        return $this->hasMany(LahanVariabel::class, 'id_topik');
    }

    // Get data through variabel relationship
    public function data()
    {
        return $this->hasManyThrough(
            LahanData::class,
            LahanVariabel::class,
            'id_topik', // Foreign key on lahan_variabel table
            'id_variabel', // Foreign key on lahan_data table
            'id', // Local key on lahan_topik table
            'id' // Local key on lahan_variabel table
        );
    }
}

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
    // The `lahan_topik` table does not have created_at/updated_at columns.
    // Disable Eloquent timestamps to avoid insert errors for missing columns.
    public $timestamps = false;

    // DB column is `deskripsi` but UI and Livewire use `nama`.
    // Keep deskripsi fillable and map a virtual `nama` attribute to it.
    // Allow setting either `deskripsi` (DB column) or `nama` (UI-facing virtual
    // attribute). Adding `nama` here enables mass-assignment used by Livewire
    // create/update calls which pass ['nama' => '...'].
    protected $fillable = [
        'deskripsi',
        'nama',
    ];

    // Allow getting/setting `nama` to map to `deskripsi` for compatibility
    public function getNamaAttribute()
    {
        // Prefer explicit 'nama' when it exists (e.g., selected as alias),
        // otherwise fall back to the DB column 'deskripsi'. This avoids
        // returning null when queries use "select deskripsi as nama".
        return $this->attributes['nama'] ?? ($this->attributes['deskripsi'] ?? null);
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['deskripsi'] = $value;
    }

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

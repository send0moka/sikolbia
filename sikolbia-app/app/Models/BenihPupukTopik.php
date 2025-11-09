<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenihPupukTopik extends Model
{
    use HasFactory;

    protected $table = 'benih_pupuk_topik';
    
    public $timestamps = false;

    protected $fillable = [
        'deskripsi',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    // Relationships
    public function variabels()
    {
        return $this->hasMany(BenihPupukVariabel::class, 'id_topik');
    }

    // Scopes
    public function scopeWithVariabelCount($query)
    {
        return $query->withCount('variabels');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->deskripsi ?: "Topik #{$this->id}";
    }

    // Static methods
    public static function getDropdownOptions()
    {
        return static::orderBy('deskripsi')->pluck('deskripsi', 'id')->toArray();
    }
}

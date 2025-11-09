<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool hasRole($role)
 * @method bool hasAnyRole($roles)
 * @method bool hasAllRoles($roles)
 * @method \Illuminate\Database\Eloquent\Collection getRoleNames()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany roles()
 * @method self assignRole($role)
 * @method self removeRole($role)
 * @method self syncRoles($roles)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user is admin or superadmin
     * Helper method untuk IDE completion
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(['admin', 'superadmin']);
    }

    /**
     * Check if user is superadmin only
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }
}

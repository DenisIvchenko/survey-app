<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    private const MODERATOR_REPUTATION_THRESHOLD = 500;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'reputation' => 'integer',
    ];

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function isModerator(): bool
    {
        if (isset($this->role) && $this->role === 'moderator') {
            return true;
        }

        return $this->reputation >= self::MODERATOR_REPUTATION_THRESHOLD;
    }
}

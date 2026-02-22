<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'profile_image',
        'post_filter',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_image' => 'string',
            'post_filter' => 'string',
        ];
    }

    /**
     * Get the user's profile image URL
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (! $this->profile_image) {
            return null;
        }

        return asset('storage/'.$this->profile_image);
    }

    /**
     * Check if user is banned
     */
    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    /**
     * Check if user is verified
     */
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->status === 'admin';
    }

    /**
     * Check if user is a regular user
     */
    public function isRegularUser(): bool
    {
        return $this->status === 'user';
    }

    /**
     * Get the badge class based on user status
     */
    public function getBadgeClass(): string
    {
        return match ($this->status) {
            'admin' => 'admin-badge',
            'verified' => 'verified-badge',
            default => ''
        };
    }

    /**
     * Get the badge icon path based on user status
     */
    public function getBadgeIconPath(): ?string
    {
        return match ($this->status) {
            'admin' => asset('images/user/verified-badge-gold.svg'),
            'verified' => asset('images/user/verified-badge.svg'),
            default => null
        };
    }

    /**
     * Get the badge tooltip text based on user status
     */
    public function getBadgeTooltip(): ?string
    {
        return match ($this->status) {
            'admin' => 'Admin',
            'verified' => 'Verified',
            default => null
        };
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
}

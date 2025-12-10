<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Native\Mobile\Facades\SecureStorage;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'lang',
        'user_id',
        'phone',
        'phone_verified_at',
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
            'phone_verified_at' => 'datetime',
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
     * Get the authenticated user's ID.
     * Uses Laravel auth if available, falls back to SecureStorage for mobile.
     */
    public static function currentUserId(): ?string
    {
        if (auth()->check()) {
            return auth()->user()->user_id;
        }

        return SecureStorage::get('user_id');
    }

    /**
     * Check if a user is authenticated.
     */
    public static function isAuthenticated(): bool
    {
        return auth()->check() || ! empty(SecureStorage::get('user_id'));
    }
}

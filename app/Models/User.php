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
    protected $guarded = [];

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

    /**
     * Create a minimal user record if it does not already exist.
     * No updates are performed if the user exists.
     */
    public static function saveMini(array $data): void
    {
        // Defensive: require user_id
        if (empty($data['user_id'])) {
            return;
        }

        self::insertOrIgnore([
            'user_id'    => $data['user_id'],
            'name'       => $data['name']       ?? trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
            'email'      => $data['email']      ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name'  => $data['last_name']  ?? null,
            'lang'       => $data['lang']       ?? null,
            'phone'      => $data['phone']      ?? null,
            'avatar'     => $data['avatar']     ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

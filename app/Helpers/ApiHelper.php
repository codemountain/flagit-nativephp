<?php

namespace App\Helpers;

use App\Services\ApiAuthService;
use Illuminate\Support\Facades\Auth;

class ApiHelper
{
    /**
     * Get API service instance for current user
     */
    public static function api(): ApiAuthService
    {
        return app(ApiAuthService::class)->forUser();
    }

    /**
     * Get the current authenticated user's API data
     */
    public static function getCurrentUserApiData(): ?array
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return $user->api_data; // Using the accessor
    }

    /**
     * Check if current user is authenticated via API
     */
    public static function isApiAuthenticated(): bool
    {
        $user = Auth::user();

        return $user && $user->hasValidApiToken();
    }

    /**
     * Get user's preferred language from API data
     */
    public static function getUserLanguage(): ?string
    {
        $user = Auth::user();

        return $user?->lang;
    }

    /**
     * Patrol API Methods
     */

    /**
     * Start patrol session
     */
    public static function startPatrol(): array
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        try {
            return self::api()->post("patrol/{$user->external_user_id}/start");
        } catch (\Exception $e) {
            ray('Patrol start API failed:', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Share current location during patrol
     */
    public static function sharePatrolLocation(float $latitude, float $longitude, ?float $accuracy = null): array
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        $data = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'accuracy' => $accuracy,
            'timestamp' => now()->toISOString(),
        ];

        try {
            return self::api()->post("patrol/{$user->external_user_id}/share", $data);
        } catch (\Exception $e) {
            ray('Patrol share API failed:', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Stop patrol session
     */
    public static function stopPatrol(): array
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        try {
            return self::api()->post("patrol/{$user->external_user_id}/stop");
        } catch (\Exception $e) {
            ray('Patrol stop API failed:', $e->getMessage());
            throw $e;
        }
    }
}

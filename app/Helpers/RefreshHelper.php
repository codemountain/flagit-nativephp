<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class RefreshHelper
{
    /**
     * Check if a refresh is needed based on a timestamp field and delay.
     *
     * @param Model $model The model instance to check (usually auth()->user())
     * @param string $fieldName The timestamp field name (e.g., 'last_reports_synced_at')
     * @param int $delayMinutes The refresh delay in minutes
     * @param bool $debug Whether to output debug information
     * @return bool True if refresh is needed, false otherwise
     */
    public static function needsRefresh(Model $model, string $fieldName, int $delayMinutes, bool $debug = false): bool
    {
        $currentTime = now();
        $lastSyncTime = $model->{$fieldName};
        $refreshThreshold = $currentTime->copy()->subMinutes($delayMinutes);
        
        $shouldRefresh = is_null($lastSyncTime) || $lastSyncTime < $refreshThreshold;
        
        if ($debug) {
            $minutesSinceLastSync = $lastSyncTime ? $currentTime->diffInMinutes($lastSyncTime) : null;
            
            ray("RefreshHelper Debug for {$fieldName}:", [
                'current_time' => $currentTime->toDateTimeString(),
                'last_sync_time' => $lastSyncTime?->toDateTimeString() ?? 'Never',
                'refresh_threshold' => $refreshThreshold->toDateTimeString(),
                'delay_minutes' => $delayMinutes,
                'minutes_since_last_sync' => $minutesSinceLastSync ?? 'Never synced',
                'should_refresh' => $shouldRefresh,
                'reason' => $shouldRefresh 
                    ? (is_null($lastSyncTime) ? 'Never synced before' : "Last sync was {$minutesSinceLastSync} minutes ago (> {$delayMinutes} minutes)")
                    : "Last sync was {$minutesSinceLastSync} minutes ago (< {$delayMinutes} minutes)"
            ]);
        }
        
        return $shouldRefresh;
    }
    
    /**
     * Update the timestamp field to mark as refreshed.
     *
     * @param Model $model The model instance to update
     * @param string $fieldName The timestamp field name
     * @param Carbon|null $timestamp The timestamp to set (defaults to now())
     * @return void
     */
    public static function markAsRefreshed(Model $model, string $fieldName, ?Carbon $timestamp = null): void
    {
        $model->{$fieldName} = $timestamp ?? now();
        $model->save();
    }
    
    /**
     * Check if refresh is needed using a config key for delay.
     *
     * @param Model $model The model instance to check
     * @param string $fieldName The timestamp field name
     * @param string $configKey The config key for delay (e.g., 'services.pt.report_refresh_delay')
     * @param int $defaultDelay Default delay if config is not found
     * @param bool $debug Whether to output debug information
     * @return bool True if refresh is needed, false otherwise
     */
    public static function needsRefreshFromConfig(Model $model, string $fieldName, string $configKey, int $defaultDelay = 30, bool $debug = false): bool
    {
        $delayMinutes = config($configKey, $defaultDelay);
        return static::needsRefresh($model, $fieldName, $delayMinutes, $debug);
    }
    
    /**
     * Convenience method for checking user refresh needs.
     *
     * @param string $fieldName The user timestamp field name
     * @param string $configKey The config key for delay
     * @param int $defaultDelay Default delay if config is not found
     * @param bool $debug Whether to output debug information
     * @return bool True if refresh is needed, false otherwise
     */
    public static function userNeedsRefresh(string $fieldName, string $configKey, int $defaultDelay = 30, bool $debug = false): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        return static::needsRefreshFromConfig($user, $fieldName, $configKey, $defaultDelay, $debug);
    }
    
    /**
     * Convenience method for marking user field as refreshed.
     *
     * @param string $fieldName The user timestamp field name
     * @param Carbon|null $timestamp The timestamp to set (defaults to now())
     * @return void
     */
    public static function markUserAsRefreshed(string $fieldName, ?Carbon $timestamp = null): void
    {
        $user = auth()->user();
        if ($user) {
            static::markAsRefreshed($user, $fieldName, $timestamp);
        }
    }
}

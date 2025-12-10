<?php

namespace App\Models;

use App\Enums\SyncModel;
use Illuminate\Database\Eloquent\Model;

class UserSync extends Model
{
    protected $fillable = [
        'user_id',
        'model',
        'last_synced_at',
        'notified_at',
    ];

    protected function casts(): array
    {
        return [
            'model' => SyncModel::class,
            'last_synced_at' => 'datetime',
            'notified_at' => 'datetime',
        ];
    }

    /**
     * Check if a sync is needed for a given model type.
     */
    public static function needsSync(string $userId, SyncModel $model, int $delayMinutes = 1440): bool
    {
        $sync = self::where('user_id', $userId)
            ->where('model', $model->value)
            ->first();

        if (! $sync || ! $sync->last_synced_at) {
            return true;
        }

        return $sync->last_synced_at->addMinutes($delayMinutes)->isPast();
    }

    /**
     * Record that a sync occurred.
     */
    public static function recordSync(string $userId, SyncModel $model): self
    {
        return self::updateOrCreate(
            ['user_id' => $userId, 'model' => $model->value],
            ['last_synced_at' => now()]
        );
    }

    /**
     * Get the last sync time for a model.
     */
    public static function getLastSync(string $userId, SyncModel $model): ?self
    {
        return self::where('user_id', $userId)
            ->where('model', $model->value)
            ->first();
    }

    /**
     * Check if user should be redirected to sync (needs sync AND hasn't been notified this cycle).
     */
    public static function shouldRedirectToSync(string $userId, SyncModel $model, int $delayMinutes = 1440): bool
    {
        if (! self::needsSync($userId, $model, $delayMinutes)) {
            return false;
        }

        $sync = self::where('user_id', $userId)
            ->where('model', $model->value)
            ->first();

        // No record exists - first time, should redirect
        if (! $sync) {
            return true;
        }

        // Never notified - should redirect
        if (! $sync->notified_at) {
            return true;
        }

        // Notified before last sync completed - new cycle, should redirect
        if ($sync->last_synced_at && $sync->notified_at < $sync->last_synced_at) {
            return true;
        }

        // Already notified this cycle
        return false;
    }

    /**
     * Record that user was notified/redirected to sync.
     */
    public static function recordNotified(string $userId, SyncModel $model): self
    {
        return self::updateOrCreate(
            ['user_id' => $userId, 'model' => $model->value],
            ['notified_at' => now()]
        );
    }
}

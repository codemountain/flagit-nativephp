<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Report extends BaseModel
{
    /**
     * The attributes that are not mass assignable.
     * Using empty array to allow mass assignment of all attributes.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lat' => 'decimal:8',
            'long' => 'decimal:8',
            'distance' => 'decimal:2',
            'is_urgent' => 'boolean',
            'exif_data' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'images'             => 'array',
            'assigned_user_ids'  => 'array',
            'category_names'     => 'array',
            'skill_names'        => 'array',
            'equipment_names'    => 'array',
            'material_names'     => 'array',
            'task_names'         => 'array',
        ];
    }

    /**
     * Scope to filter reports created by a specific user.
     */
    public function scopeCreatedBy(Builder $query, string $userId): Builder
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope to get reports assigned to a specific user.
     * Uses LIKE query with boundaries to match userId in CSV field.
     */
    public function scopeAssignedTo(Builder $query, string $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('assigned_user_ids', $userId)
                ->orWhere('assigned_user_ids', 'LIKE', $userId.',%')
                ->orWhere('assigned_user_ids', 'LIKE', '%,'.$userId.',%')
                ->orWhere('assigned_user_ids', 'LIKE', '%,'.$userId);
        });
    }

    /**
     * Check if a user is in the assigned_user_ids CSV field.
     */
    public function isAssignedTo(string $userId): bool
    {
        if (empty($this->assigned_user_ids)) {
            return false;
        }

        return in_array($userId, explode(',', $this->assigned_user_ids));
    }

    /**
     * Scope to order reports by most recent first.
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get all of the report's notes.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable', 'notable_type', 'notable_id', 'report_id')->orderBy('created_at', 'desc');
    }

    public static function saveListFromApi($data): array
    {
        $reports = [];
        // loop and call single method
        foreach ($data['data'] as $item) {
            $reports['data'][] = static::saveSingleFromApi($item)->toArray();
            if(!empty($item['notes'])) Note::saveListFromApi($item['notes'],$item);
            if(!empty($item['worklogs'])) Worklog::saveListFromApi($item['worklogs'],$item);
        }

        $reports['total'] = $data['total'] ?? null;

        return $reports;
    }

    public static function saveSingleFromApi($data)
    {

        $report = static::updateOrCreate(
            ['report_id' => $data['report_id']],
            [
                'category' => $data['category'],
                'title' => $data['title'],
                'description' => $data['description'],
                'network_name' => $data['network_name'],
                'trail_name' => $data['trail_name'],
                'image' => $data['image'],
                'images' => $data['images'],
                'thumb' => $data['thumb'],
                'lat' => $data['lat'],
                'long' => $data['long'],
                // NOTE: Excluding 'image' and 'thumb' to preserve base64 data
                'status' => $data['status'],
                'elapsed' => $data['elapsed'],
                'team_id' => $data['team_id'],
                'network_id' => $data['network_id'],
                'slug' => $data['slug'],
                'is_urgent' => $data['is_urgent'],
                'network_logo_url' => $data['network_logo_url'],
                'distance' => $data['distance'],
                'created_at' => $data['created_at'],
                'updated_at' => now(),
                'assigned_user_ids' => $data['assigned_user_ids'] ?? null,
                'created_by' => $data['created_by'] ?? null,
                'created_by_name' => $data['created_by_name'] ?? null,
                'created_by_email' => $data['created_by_email'] ?? null,
                'category_names' => $data['category_names'] ?? null,
                'skill_names' => $data['skill_names'] ?? null,
                'equipment_names' => $data['equipment_names'] ?? null,
                'material_names' => $data['material_names'] ?? null,
                'task_names' => $data['task_names'] ?? null,
            ]
        );
        if(!empty($data['creator'])) User::saveMini($data['creator']);

        return $report;

    }
    public static function saveImagesArray($data)
    {
        $report = self::whereReportId($data['report_id'])->first();
        if($report){
            $report->update(['images' => $data['images'] ?? null,'updated_at' => $data['updated_at'] ?? now()]);
        }
        return $report;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
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
        ];
    }

    public static function saveListFromApi($data) : array
    {
        $reports = [];
        //loop and call single method
        foreach ($data as $item) {
            $reports[] = static::saveSingleFromApi($item)->toArray();
        }
        return $reports;
    }

    public static function saveSingleFromApi($data)
    {
        $createdAt = null;
        if (isset($data['created_date'])) {
            try {
                // Carbon can parse ISO 8601 format directly
                $createdAt = \Carbon\Carbon::parse($data['created_date']);
            } catch (\Exception $e) {
                // Fallback to current time if parsing fails
                $createdAt = now();
            }
        }

       $report = static::updateOrCreate(
            ['report_id' => $data['report_id']],
            [
                'category' => $data['category'],
                'title' => $data['title'],
                'description' => $data['description'],
                'network_name' => $data['network_name'],
                'trail_name' => $data['trail_name'],
                'image' => $data['image'],
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
                'created_at' => $createdAt,
                'updated_at' => now(),
                'assigned_user_ids' => $data['assigned_user_ids'] ?? null,
                'created_by' => $data['created_by'] ?? null,
                'category_names' => $data['category_names'] ?? null,
                'skill_names' => $data['skill_names'] ?? null,
                'equipment_names' => $data['equipment_names'] ?? null,
                'material_names' => $data['material_names'] ?? null,
                'task_names' => $data['task_names'] ?? null,
            ]
        );
        return $report;
        /*  {
    "category": "mtb",
    "title": "cloudburst Cafe",
    "description": "testing",
    "network_name": "Orphan flags",
    "trail_name": "cloudburst Cafe",
    "lat": 49.735288,
    "long": -123.1324544,
    "image": "https:\/\/pixeltrail.s3.us-east-1.amazonaws.com\/local\/actionit\/report_photos\/0BTlqOzrH5_1747669079.jpg",
    "thumb": "https:\/\/pixeltrail.s3.us-east-1.amazonaws.com\/local\/thumbs\/bd\/xy\/hvcpits800k0gowcww4sc.jpg?crop=100x100&p=%2F0BTlqOzrH5_1747669079.jpg&s=fy",
    "status": "submitted",
    "created_at": "19 May 2025 @ 11:38",
    "created_date": "2025-05-19T15:38:00.000000Z",
    "elapsed": "6 months ago",
    "report_id": "01jvmk3pt3yabj4mksx07xrg8q",
    "team_id": "01jqw8drzd1xev5w1e5tv3mkc8",
    "network_id": "01jqw8drzd1xev5w1e5tv3mkc8",
    "slug": "orphan-flags",
    "is_urgent": false,
    "network_logo_url": null,
    "distance": null,
    "assigned_user_ids": "01j6w3aqz1mf3ygemb9cyaaq8m,01jkvf42krt68wj1jfrf7mk9x6,01j6w3ar5vjnnqwkvdej21497w,01j91p2eb07seknnw80hawz31x,01jqvyjpj9fwf0wkpvs5hnc61z",
    "created_by": "01jqvyjpj9fwf0wkpvs5hnc61z",
    "notes": [],
    "notes_count": 0,
    "category_names": "",
    "skill_names": null,
    "material_names": null,
    "task_names": null,
    "equipment_names": null
  },*/
    }
}

<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Report;

class GeoJsonFrom
{
    public static function reports($reports)
    {
        $features = [];
        foreach ($reports as $report) {
            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'id' => $report->id,
                    'title' => $report->title,
                    'description' => $report->description,
                    'status' => $report->status,
                    'is_urgent' => $report->is_urgent,
                    'network_name' => $report->network_name,
                    'trail_name' => $report->trail_name,
                    'image' => $report->image,
                    'category' => $report->category,
                    'created_at' => $report->created_at->toDateTimeString(),
                    'updated_at' => $report->updated_at->toDateTimeString(),
                    'marker' => asset('icons/markers/'.$report->status.'.svg')
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        $report->long,
                        $report->lat,
                    ],
                ],
            ];
        }
        return json_encode([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}

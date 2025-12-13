<?php

namespace App\Models;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Worklog extends BaseModel
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
    protected $casts = [
        'performed_at' => 'datetime',
        'paid'         => 'bool',
        'solo'         => 'bool',
        'approved'     => 'bool',
    ];

    /**
     * Get the parent workable model (report, etc.).
     */
    public function workable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the parent workable model (report, etc.).
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created this note.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by', 'user_id');
    }

    /**
     * Create or update a note from API response data.
     *
     * @param  array  $data
     * @param  Model  $parentModel  The model this note belongs to
     * @return static
     */

    public static function saveListFromApi($data, $parentModel): array
    {
        $worklogs = [];
        // loop and call single method
        foreach ($data as $item) {
            $worklogs[] = static::saveSingleFromApi($item,$parentModel);
            if(!empty($item['attachments'])) Attachment::saveListFromApi($item['attachments'],$item);
        }


        return $worklogs;
    }

    public static function saveSingleFromApi(array $data, $parentModel = null): static
    {
        $worklog = static::updateOrCreate(
        // Match on the remote “worklog_id” (your API’s stable identifier)
            ['worklog_id' => $data['worklog_id'] ?? $data['id'] ?? null],
            [
                'description'  => $data['description'] ?? null,
                'duration'     => (int) ($data['duration'] ?? 0),

                // Prefer ISO string; store as timestamp
                'performed_at' => $data['performed_at'] ?? null,
                'performed_by' => $data['performed_by'] ?? null,

                'paid'     => (bool) ($data['paid'] ?? false),
                'solo'     => (bool) ($data['solo'] ?? false),
                'approved' => (bool) ($data['approved'] ?? false),

                // approved_by may be false OR a user_id/ULID
                'approved_by' => $data['approved_by'] ?? null,

                // Morph to workable (if parent provided, prefer it)
                'workable_type' => self::normalizeMorphType($data['workable_type']),
                'workable_id'   => $data['workable_id'] ?? null,

                'updated_at' => $data['updated_at'] ?? null,
                'created_at' => $data['created_at'] ?? null
            ]);

        if(!empty($data['performer'])) User::saveMini($data['performer']);
        if(!empty($data['approver'])) User::saveMini($data['approver']);

        return $worklog;
    }


    public function report() {
        return $this->belongsTo(Report::class, 'workable_id', 'report_id');
    }


}

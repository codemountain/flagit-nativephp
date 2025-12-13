<?php

namespace App\Models;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
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
    public function attachable(): MorphTo
    {
        return $this->morphTo();
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
        $attachments = [];
        // loop and call single method
        foreach ($data as $item) {
            $attachments[] = static::saveSingleFromApi($item,$parentModel);
        }

        return $attachments;
    }

    public static function saveSingleFromApi(array $data, $parentModel = null): static
    {
        $attachment = static::updateOrCreate(
        // Match on the remote “worklog_id” (your API’s stable identifier)
            ['attachment_id' => $data['id'] ?? null],
            [
                'app_key'  => $data['app_key'] ?? null,

                'url'     => $data['url'] ?? null,
                'file_path' => $data['file_path'] ?? null,

                'attachable_type'     => $data['attachable_type'] ?? null,
                'attachable_id'     => $data['attachable_id'] ?? null,

                'updated_at' => $data['updated_at'] ?? null,
                'created_at' => $data['created_at'] ?? null
            ]);

        return $attachment;
    }


    public function note() {
        return $this->belongsTo(Note::class, 'attachable_id', 'note_id');
    }

    public function worklog() {
        return $this->belongsTo(Worklog::class, 'attachable_id', 'worklog_id');
    }

}

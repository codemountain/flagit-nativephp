<?php

namespace App\Models;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the parent noteable model (report, etc.).
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all of the note's attachments.
     */
//    public function attachments(): MorphMany
//    {
//        return $this->morphMany(Attachment::class, 'attachable');
//    }

    /**
     * Get the user who created this note.
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id', 'user_id');
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
        $notes = [];
        // loop and call single method
        foreach ($data as $item) {
            $notes[] = static::saveSingleFromApi($item,$parentModel);
        }


        return $notes;
    }

    public static function saveSingleFromApi(array $data, $parentModel = null): static
    {
        $note = static::updateOrCreate(
            ['note_id' => $data['id']],
            [
                'from_user_id' => $data['from_user_id'],
                'from_name' => $data['from_name'],
                'app_key' => $data['app_key'],
                'content' => $data['content'],
                // NOTE: Excluding 'default_image' to preserve base64 data
//                'noteable_type' => get_class($parentModel),
                'noteable_id' => !empty($parentModel) ? $parentModel['report_id'] : $data['noteable_id'],
                'created_at' => $data['created_at'],
                'updated_at' => now(),
                'default_image' => $data['default_image'] ?? null,
            ]

        );

//        // Smart image conversion - only convert when necessary
//        MediaHelper::convertUrlToBase64IfNeeded($note, 'default_image', $data['default_image'] ?? '', 'default_image_source_url');
//
//        // Sync attachments if they exist
//        if (isset($data['attachments']) && is_array($data['attachments'])) {
//            foreach ($data['attachments'] as $attachmentData) {
//                Attachment::createFromApiResponse($attachmentData, $note);
//            }
//        }

        return $note;
    }

    public function getNoteDefaultImageAttribute()
    {

        if(!empty($this->default_image)) {
            dd($this->default_image);
            return $this->default_image;
        }

        if($this->attachments && $this->attachments->count() > 0) {
            // Get the latest attachment by created_at  timestamp
           // ray($this->attachments->sortByDesc('created_at')->first());
            $latestAttachment = $this->attachments->sortByDesc('created_at')->first();
            return $latestAttachment->url;
        }


        return null;
    }
    /**
     * Check if the note has any attachments/images
     *
     * @return bool
     */
    public function getHasImagesAttribute(): bool
    {
       // ray('Has images: ', $this->attachments->count() > 0);
        $hasImages = $this->attachments && $this->attachments->count() > 0;
        if(!$hasImages) {
            $hasImages = !empty($this->default_image);
        }
        return $hasImages;
    }

    /**
     * Create a local report from form data.
     */
    public static function createLocalNote(array $data): static
    {
        return static::create([
            'external_id' => null,
            'from_user_id' => auth()->user()->external_user_id,
            'from_name' => auth()->user()->name,
            'app_key' => 'actionit',
            'content' => $data['content'],
            'default_image' => (!empty($data['default_image'])) ? $data['default_image'] : null,
            'noteable_type' => 'App\Models\Report',
            'noteable_id' => $data['noteable_id'],
        ]);

    }

    /**
     * Get all unsynced reports for a user.
     */
    public static function getUnsyncedForUser(string $externalUserId)
    {
        return static::where('from_user_id', $externalUserId)
            ->unsynced()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Scope to get only unsynced notes (local-only).
     */
    public function scopeUnsynced($query)
    {
        return $query->whereNull('external_id');
    }

    /**
     * Scope to get only synced notes.
     */
    public function scopeSynced($query)
    {
        return $query->whereNotNull('external_id');
    }

    public function report() {
        return $this->belongsTo(Report::class, 'noteable_id', 'id');
    }


}

<?php

namespace App\Helpers;

use App\Services\ApiAuthService;
use Illuminate\Support\Facades\Auth;

class MediaHelper
{
    public static function checkAndGet($url)
    {
        // Handle null or empty URLs
        if (empty($url)) {
            return asset('images/placeholder.jpg');
        }

        //using laravel http perform a get on the url and check if 200, if not send back a placeholder image from asset('images/placeholder.jpg')
        //check first if $url is actually a base 64 encoded image, if so just return that.
        if (strpos($url, 'data:image') === 0) {
            return $url;
        }

        try {
            $response = \Http::get($url);
            if ($response->status() == 200) {
                return $url;
            } else {
                return asset('images/placeholder.jpg');
            }
        } catch (\Exception $e) {
            return asset('images/placeholder.jpg');
        }
    }

    public static function getAsLocal($model, $field = 'image') //$type is image or thumb
    {
        // Handle null or empty field values
        if (empty($model->$field)) {
            return asset('images/placeholder.jpg');
        }

        // check if the $url is actually a base 64 encoded image, if so just return that. if not get the image and store it as base64 on the model. depending on the model make it Report as
        //report_image or report_thumb or note_image or note_thumb
        if (strpos($model->$field, 'data:image') === 0) {
            return $model->$field;
        }
        //if we are we have a url, we need to check if it exists, if not we send back the place holder
        try {
            $response = \Http::get($model->$field);
            if ($response->status() == 200) {
                //great we have a valid $url, we now need to get the image, and save it as base64 on the model.
                $data = base64_encode($response->body());
                $mime = $response->header('Content-Type');
                $image = "data:$mime;base64,$data";
                $model->$field = $image;
                $model->save();
                return $image;
            } else {
                return asset('images/placeholder.jpg');
            }
        } catch (\Exception $e) {
            return asset('images/placeholder.jpg');
        }

    }

    /**
     * Convert image URL to base64 for offline storage.
     * Centralized method to handle image conversion across all models.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model instance
     * @param string $field The field name containing the image URL
     * @return void
     */
    public static function convertUrlToBase64($model, string $field): void
    {
        // Skip if field is empty or already base64
        if (empty($model->$field) || str_starts_with($model->$field, 'data:image')) {
            return;
        }

        // Validate URL format
        if (!filter_var($model->$field, FILTER_VALIDATE_URL)) {
            $modelName = class_basename($model);
            $modelId = $model->id ?? $model->external_id ?? 'unknown';
            ray("⚠️ Invalid URL format for {$field} in {$modelName} {$modelId}: {$model->$field}");
            return;
        }

        try {
            static::getAsLocal($model, $field);
            $modelName = class_basename($model);
            $modelId = $model->id ?? $model->external_id ?? 'unknown';
            ray("✅ Converted {$field} URL to base64 for {$modelName}: {$modelId}");
        } catch (\Exception $e) {
            // Log the error but don't fail the entire sync process
            $modelName = class_basename($model);
            $modelId = $model->id ?? $model->external_id ?? 'unknown';

            \Log::warning("Failed to convert {$field} for {$modelName} {$modelId}", [
                'model' => $modelName,
                'model_id' => $modelId,
                'field' => $field,
                'url' => $model->$field,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            ray("❌ Failed to convert {$field} for {$modelName} {$modelId}: " . $e->getMessage());
        }
    }

    /**
     * Smart image conversion that only processes when necessary.
     * Compares API URL with stored source URL and only converts when:
     * 1. Record is new (no existing source URL)
     * 2. API URL differs from stored source URL
     * 3. Current field contains URL instead of base64
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model instance
     * @param string $field The field name containing the image
     * @param string $apiUrl The URL from the API response
     * @param string $sourceField The field name for storing the source URL
     * @return bool Whether conversion was performed
     */
    public static function convertUrlToBase64IfNeeded($model, string $field, string $apiUrl, string $sourceField): bool
    {
        // Skip if API URL is empty
        if (empty($apiUrl)) {
            return false;
        }

        // Get the currently stored source URL
        $storedSourceUrl = $model->$sourceField ?? null;

        // Determine if conversion is needed
        $needsConversion = false;
        $reason = '';

        if (empty($storedSourceUrl)) {
            // New record or first time setting this field
            $needsConversion = true;
            $reason = 'new record';
        } elseif ($apiUrl !== $storedSourceUrl) {
            // API URL has changed
            $needsConversion = true;
            $reason = 'URL changed';
        } elseif (!empty($model->$field) && !str_starts_with($model->$field, 'data:image')) {
            // Current field contains URL instead of base64 (fallback case)
            $needsConversion = true;
            $reason = 'field contains URL';
        }

        if (!$needsConversion) {
            // Skip ray logging for performance - only log when conversion happens
            return false;
        }

        // Perform the conversion
        try {
            // Update the field with the new URL first
            $model->$field = $apiUrl;

            // Store the source URL for future comparison BEFORE conversion
            $model->$sourceField = $apiUrl;

            // Save the model first to ensure both fields are persisted
            $model->save();

            // Convert URL to base64 (this will save the model again with base64 data)
            static::getAsLocal($model, $field);

            $modelName = class_basename($model);
            $modelId = $model->id ?? $model->external_id ?? 'unknown';
            ray("✅ Converted {$field} for {$modelName} {$modelId}: {$reason}");

            return true;

        } catch (\Exception $e) {
            $modelName = class_basename($model);
            $modelId = $model->id ?? $model->external_id ?? 'unknown';

            \Log::warning("Failed to convert {$field} for {$modelName} {$modelId}", [
                'model' => $modelName,
                'model_id' => $modelId,
                'field' => $field,
                'api_url' => $apiUrl,
                'stored_source_url' => $storedSourceUrl,
                'reason' => $reason,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            ray("❌ Failed to convert {$field} for {$modelName} {$modelId}: " . $e->getMessage());
            return false;
        }
    }
}

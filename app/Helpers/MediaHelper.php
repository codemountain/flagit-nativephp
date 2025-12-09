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

    /**
     * Compress base64 image to reduce size
     * EXIF data is preserved separately in the $report->exif_data field
     */
    public static function compressBase64Image(string $base64Image, int $quality = 80): string
    {
        try {
            // Extract the base64 data and mime type
            if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                \Log::warning('Invalid base64 image format, returning original');

                return $base64Image;
            }

            $imageType = $matches[1];
            $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($base64Data);

            if ($imageData === false) {
                \Log::warning('Failed to decode base64 image, returning original');

                return $base64Image;
            }

            // Create image resource from string
            $image = imagecreatefromstring($imageData);

            if ($image === false) {
                \Log::warning('Failed to create image resource, returning original');

                return $base64Image;
            }

            // Start output buffering
            ob_start();

            // Compress based on image type
            switch ($imageType) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($image, null, $quality);
                    break;
                case 'png':
                    // PNG quality is 0-9 (inverted from JPEG), convert 80 -> 2
                    $pngQuality = floor((100 - $quality) / 10);
                    imagepng($image, null, $pngQuality);
                    break;
                case 'webp':
                    imagewebp($image, null, $quality);
                    break;
                default:
                    // For other types, return original
                    imagedestroy($image);
                    ob_end_clean();

                    return $base64Image;
            }

            // Get the compressed image data
            $compressedData = ob_get_clean();
            imagedestroy($image);

            // Convert back to base64
            $compressedBase64 = "data:image/{$imageType};base64,".base64_encode($compressedData);

            $originalSize = strlen($base64Data);
            $compressedSize = strlen(base64_encode($compressedData));
            $reduction = round((1 - ($compressedSize / $originalSize)) * 100, 2);

            \Log::info('Image compressed', [
                'original_size' => number_format($originalSize),
                'compressed_size' => number_format($compressedSize),
                'reduction_percent' => $reduction.'%',
                'type' => $imageType,
            ]);

            return $compressedBase64;
        } catch (\Exception $e) {
            \Log::error('Image compression failed: '.$e->getMessage());

            return $base64Image; // Return original on error
        }
    }
}

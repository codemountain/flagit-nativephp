<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class NativeImages
{
    /**
     * Maximum file size for preview generation (in bytes)
     * Preview will be generated client-side using Canvas API
     */
    const MAX_PREVIEW_SIZE = 300 * 1024; // 300KB for preview display

    /**
     * Process an image from NativePHP camera/gallery
     *
     * @param  string  $sourcePath  Path to the source image file
     * @param  string  $directory  Storage directory (default: 'photos')
     * @return array Array with processing results
     */
    public static function process(string $sourcePath, string $directory = 'photos'): array
    {
        try {
            // Detect mime type
            $mimeType = self::detectMimeType($sourcePath);

            // Generate unique filename with correct extension
            $extension = self::getExtensionFromMime($mimeType);
            $filename = $directory.'/'.uniqid('img_').'_'.time().'.'.$extension;

            // Read file contents
            $contents = file_get_contents($sourcePath);
            $fileSize = strlen($contents);

            // Store original in Laravel storage for persistence
            Storage::disk('public')->put($filename, $contents);

            // Always encode to base64 for processing
            $base64 = base64_encode($contents);
            $fullDataUrl = "data:$mimeType;base64,$base64";

            // Prepare response data
            $result = [
                'success' => true,
                'filename' => $filename,
                'size' => $fileSize,
                'size_kb' => round($fileSize / 1024, 2),
                'size_mb' => round($fileSize / (1024 * 1024), 2),
                'mime_type' => $mimeType,
                'needs_preview' => $fileSize > self::MAX_PREVIEW_SIZE,
                'display_url' => $fileSize <= self::MAX_PREVIEW_SIZE ? $fullDataUrl : '',
                'full_data_url' => $fullDataUrl, // Always include full data for preview generation
                'storage_path' => Storage::disk('public')->path($filename),
            ];

            // Clean up the original temp file
            if (file_exists($sourcePath)) {
                @unlink($sourcePath);
            }

            return $result;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'filename' => null,
                'display_url' => '',
            ];
        }
    }

    /**
     * Detect MIME type from file
     */
    private static function detectMimeType(string $path): string
    {
        // Try using mime_content_type if available
        if (function_exists('mime_content_type')) {
            $detected = mime_content_type($path);
            if ($detected && in_array($detected, self::getSupportedMimeTypes())) {
                return $detected;
            }
        }

        // Fallback: check file signature (magic bytes)
        $handle = fopen($path, 'rb');
        if (! $handle) {
            return 'image/jpeg'; // Default fallback
        }

        $bytes = fread($handle, 12);
        fclose($handle);

        // Check magic bytes for common image formats
        if (substr($bytes, 0, 3) === "\xFF\xD8\xFF") {
            return 'image/jpeg';
        }

        if (substr($bytes, 0, 8) === "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") {
            return 'image/png';
        }

        // WebP detection
        if (substr($bytes, 0, 4) === 'RIFF' && substr($bytes, 8, 4) === 'WEBP') {
            return 'image/webp';
        }

        // HEIC/HEIF detection (iOS)
        if (substr($bytes, 4, 4) === 'ftyp') {
            $brand = substr($bytes, 8, 4);
            if (in_array($brand, ['heic', 'heix', 'hevc', 'hevx', 'mif1', 'msf1'])) {
                return 'image/heic';
            }
        }

        return 'image/jpeg'; // Default fallback
    }

    /**
     * Get file extension from MIME type
     */
    private static function getExtensionFromMime(string $mimeType): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/heic' => 'heic',
            'image/heif' => 'heif',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
        ];

        return $extensions[$mimeType] ?? 'jpg';
    }

    /**
     * Get list of supported MIME types
     */
    private static function getSupportedMimeTypes(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/heic',
            'image/heif',
            'image/gif',
            'image/bmp',
        ];
    }

    /**
     * Check if a file is an image based on MIME type
     */
    public static function isImage(string $mimeType): bool
    {
        return in_array($mimeType, self::getSupportedMimeTypes());
    }

    /**
     * Clean up old images from storage
     *
     * @return int Number of files deleted
     */
    public static function cleanupOldImages(string $directory = 'photos', int $daysOld = 7): int
    {
        $files = Storage::disk('public')->files($directory);
        $deleted = 0;
        $cutoffTime = now()->subDays($daysOld)->timestamp;

        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            if ($lastModified < $cutoffTime) {
                Storage::disk('public')->delete($file);
                $deleted++;
            }
        }

        return $deleted;
    }
}

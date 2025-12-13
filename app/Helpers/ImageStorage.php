<?php

namespace App\Helpers;

use Native\Mobile\Facades\SecureStorage;

class ImageStorage
{
    /**
     * Encodes the given raw type using hexadecimal encoding.
     * Same as WireChat's implementation for compatibility
     */
    public static function url(string $img): string
    {
        $base = SecureStorage::get('base_storage_url') ?? config('filesystems.base_storage_path_default');
        return $base . $img;
    }

}

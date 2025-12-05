<?php

namespace App\Helpers;

class MorphClassResolver
{
    /**
     * Encodes the given raw type using hexadecimal encoding.
     * Same as WireChat's implementation for compatibility
     */
    public static function encode(string $rawType): string
    {
        return bin2hex($rawType);
    }

    /**
     * Decodes the given hex-encoded type back to its raw string.
     * Same as WireChat's implementation for compatibility
     *
     * @return string|false
     */
    public static function decode(string $encodedType)
    {
        return hex2bin($encodedType);
    }
}

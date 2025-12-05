<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($path)
    {
        // Reconstruct the full path
        $fullPath = 'photos/'.$path;

        // Check if file exists
        if (! Storage::disk('public')->exists($fullPath)) {
            abort(404);
        }

        // Get file contents and mime type
        $file = Storage::disk('public')->get($fullPath);
        $mimeType = Storage::disk('public')->mimeType($fullPath);

        // Return image response
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=3600');
    }
}

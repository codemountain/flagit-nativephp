<?php

namespace App\Livewire;

use Flux\Flux;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Facades\Camera;

class ReportCreate extends Component
{
    public string $photoDataUrl = '';

    public $storagePath='';

    public $new_report = [
        'title' => '',
        'description' => '',
        'lat' => null,
        'long' => null,
        'image' => null,
        'is_urgent' => false,
        'type' => null,
    ];

    public function getImage()
    {
        Flux::modal('image-method')->show();
    }

    public function getImageFromCamera()
    {
        $this->photoDataUrl = '';
        $this->new_report['image'] = null;
        Flux::modal('image-method')->close();
        Camera::getPhoto();
    }

    #[OnNative(PhotoTaken::class)]
    public function handleCamera($path)
    {
        // Generate filename like the kitchen sink example
        $filename = 'photo_'.time().'.jpg';
        //$filepath = storage_path('app/public/');
        $allfile = 'photos/'.$filename;
        // Move the file to storage like the kitchen sink example
        File::move($path, Storage::path($allfile));
        $this->storagePath = implode(",",Storage::allFiles('photos'));
        // For NativePHP mobile, we need base64 for display
        // Read the file from its new location
//        $imageData = Storage::get($filename);
//        $mimeType = Storage::mimeType($filename) ?: 'image/jpeg';

        // Create data URL for display in the mobile app
        //$this->photoDataUrl = "data:$mimeType;base64,".base64_encode($imageData);
        $this->photoDataUrl = '/photos/'.$filename;
        // Store the filename for the report
        $this->new_report['image'] = $filename;
    }

    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}

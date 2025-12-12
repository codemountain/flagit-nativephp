<?php

namespace App\Livewire\Traits;

use App\Services\ReportServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Alert\ButtonPressed;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\File as MobileFile;

trait ImagePicker
{
    public string $photoDataUrl = '';

    public $storagePath='';

    public $newImage = null;

    public bool $hasGpsLocation = false;

    public string $photoGeoStatus='';

    public function getImage()
    {
        Dialog::alert(
            'Choose',
            'Source of image',
            ['Gallery', 'Photo','Cancel']
        );
    }

    #[OnNative(ButtonPressed::class)]
    public function handleAlertButton($index, $label)
    {
        switch ($index) {
            case 0:
                $this->getImageFromLibrary();
                break;
            case 1:
                $this->getImageFromCamera();
                break;
            case 2://cancel
                break;
        }
    }
    public function getImageFromCamera()
    {
        $this->photoDataUrl = '';
        $this->newImage = null;
        Camera::getPhoto();
    }

    #[OnNative(PhotoTaken::class)]
    public function handleCamera($path)
    {
        $this->hasGpsLocation = true;
        $filename = '/photos/photo_'.time().'.jpg';
        MobileFile::move($path, Storage::path($filename));
        //Log::info("Files ", print_r($this->folderFiles,true));
        $this->photoDataUrl = Storage::url($filename);
        $this->newImage = $filename;
        $this->dispatch('exif-new-image');
        $this->dispatch('got-new-image');
        $this->photoGeoStatus = __('Checking image GPS data...');
    }

    public function getImageFromLibrary()
    {
        $this->photoDataUrl = '';
        $this->newImage = null;
        Camera::pickImages();
    }

    #[On('native:'.MediaSelected::class)]
    public function handleMediaSelected($success, $files, $count)
    {
        foreach ($files as $file) {
            $this->hasGpsLocation = true;
            $filename = 'photos/photo_'.time().'.jpg';
            MobileFile::move($file['path'], Storage::path($filename));
            $this->photoDataUrl = Storage::url($filename);
            $this->newImage = $filename;
            $this->dispatch('exif-new-image');
            $this->dispatch('got-new-image');
            $this->photoGeoStatus = __('Checking image GPS data...');
        }
    }

    public function handleImageCompressionError($error)
    {
        // Clear image data
        $this->photoDataUrl = '';
        $this->newImage = '';

        // Customize message based on error type
        if (str_contains($error, 'HEIC')) {
            $message = 'HEIC format not supported. Please change iPhone camera to "Most Compatible" in Settings > Camera > Formats.';
        } elseif (str_contains($error, '2MB')) {
            $message = 'Image too large (max 2MB). Please take a lower resolution photo.';
        } else {
            $message = 'Image compression failed. Please try a different photo.';
        }

        // Notify user
        Dialog::toast($message);

        // Log for debugging
        Log::warning('Image compression failed', ['error' => $error]);
    }
}

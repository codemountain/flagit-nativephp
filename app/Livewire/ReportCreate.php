<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use Flux\Flux;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Alert\ButtonPressed;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Events\Geolocation\LocationReceived;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\Geolocation as GeolocationFacade;
use Native\Mobile\Facades\SecureStorage;

class ReportCreate extends Component
{
    Use Geo;

    public string $photoDataUrl = '';

    public $storagePath='';

    public string $photoGeoStatus='';

    public bool $hasGpsLocation = false;

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
//        Flux::modal('image-method')->show();
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
        $this->new_report['image'] = null;
        Camera::getPhoto();
    }

    #[OnNative(PhotoTaken::class)]
    public function handleCamera($path)
    {
        $this->hasGpsLocation = true;
        $filename = 'photos/photo_'.time().'.jpg';
        File::move($path, Storage::path($filename));
        //Log::info("Files ", print_r($this->folderFiles,true));
        $this->photoDataUrl = Storage::url($filename);
        $this->new_report['image'] = $filename;
        $this->dispatch('exif-new-image');
        $this->photoGeoStatus = __('Checking image GPS data...');
    }

    public function getImageFromLibrary()
    {
        $this->photoDataUrl = '';
        $this->new_report['image'] = null;
        Camera::pickImages();
    }

    #[On('native:'.MediaSelected::class)]
    public function handleMediaSelected($success, $files, $count)
    {
        foreach ($files as $file) {
            $this->hasGpsLocation = true;
            $filename = 'photos/photo_'.time().'.jpg';
            File::move($file['path'], Storage::path($filename));
            $this->photoDataUrl = Storage::url($filename);
            $this->new_report['image'] = $filename;
            $this->dispatch('exif-new-image');
            $this->photoGeoStatus = __('Checking image GPS data...');
        }
    }
    public function handleImageGPS($data)
    {
        if(!empty($data['latitude']) && !empty($data['longitude'])) {
            $this->new_report['lat'] = $data['latitude'];
            $this->new_report['long'] = $data['longitude'];
//            Dialog::toast(__('📍Got GPS lat,long data from image ✅'));
            $this->photoGeoStatus = __('GPS data found ✅');
            $this->hasGpsLocation = true;
        } else {
            $this->photoGeoStatus = __('No GPS data found ❌');
            $this->hasGpsLocation = false;
            Dialog::toast(__('No GPS data found in image OR Location permissions not available.'));
        }

    }

    public function handleImageGPSError($error)
    {
        Dialog::toast($error);
    }

    public function getLocation()
    {
        $this->hasGpsLocation = true;
        $this->photoGeoStatus = __('Checking current location...');
        GeolocationFacade::getCurrentPosition(true);
    }

    #[OnNative(LocationReceived::class)]
    public function handleLocationReceived($success = null, $latitude = null, $longitude = null, $accuracy = null, $timestamp = null, $provider = null, $error = null)
    {
        if ($success) {
            $this->photoGeoStatus = __('Current Location found ✅');
            $this->new_report['lat'] = $latitude;
            $this->new_report['long'] = $longitude;
            SecureStorage::set('current_latitude', $latitude);
            SecureStorage::set('current_longitude', $longitude);
            SecureStorage::set('current_accuracy', $accuracy);
            $this->hasGpsLocation = true;
        } else {
            $this->photoGeoStatus = __('Current location not available');
            SecureStorage::set('current_latitude', null);
            SecureStorage::set('current_longitude', null);
            SecureStorage::set('current_accuracy', null);
            $this->hasGpsLocation = false;
        }
    }

    public function createReport()
    {
        dd($this->new_report);
    }

    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}

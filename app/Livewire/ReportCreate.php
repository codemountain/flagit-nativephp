<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use App\Livewire\Traits\ReportApi;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
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
use Native\Mobile\Facades\File as MobileFile;
use Native\Mobile\Facades\Geolocation as GeolocationFacade;
use Native\Mobile\Facades\SecureStorage;

class ReportCreate extends Component
{
    Use Geo, ReportApi;

    public string $photoDataUrl = '';

    public $storagePath='';

    public string $photoGeoStatus='';

    public bool $hasGpsLocation = false;

    public string $locationSource = '';

    public $new_report = [
        'title' => 'test',
        'description' => 'test 123 lorem ipsum',
        'lat' => null,
        'long' => null,
        'image' => null,
        'is_urgent' => false,
        'type' => null,
    ];
    public function mount()
    {
        Flux::modal('map-location')->show();
        $this->new_report['lat'] = SecureStorage::get('current_latitude') ?? null;
        $this->new_report['long'] = SecureStorage::get('current_longitude') ?? null;
        Flux::modal('map-location')->close();
    }

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
        $this->new_report['image'] = null;
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
            MobileFile::move($file['path'], Storage::path($filename));
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
            $this->locationSource = __("image");
            //Flux::modal('map-location')->show();
        } else {
            $this->photoGeoStatus = __('No GPS data found ❌');
            $this->hasGpsLocation = false;
            //Dialog::toast(__('No GPS data found in image OR Location permissions not available.'));
            $this->locationSource ='';
            $this->getLocation();
        }

    }

    public function handleImageGPSError($error)
    {
        Dialog::toast($error);
    }

    public function handleImageCompressionError($error)
    {
        // Clear image data
        $this->photoDataUrl = '';
        $this->new_report['image'] = null;

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

    public function getLocation()
    {
        $this->hasGpsLocation = true;
        $this->photoGeoStatus = __('Checking for current location...');
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
            $this->locationSource = __("user location");
        } else {
            $this->photoGeoStatus = __('Current location not available');
//            SecureStorage::set('current_latitude', null);
//            SecureStorage::set('current_longitude', null);
//            SecureStorage::set('current_accuracy', null);
            $this->new_report['lat'] = SecureStorage::get('current_latitude');
            $this->new_report['long'] = SecureStorage::get('current_longitude');;
            $this->hasGpsLocation = true;
            $this->locationSource = __("last saved location");
        }

        //Flux::modal('map-location')->show();
    }

    #[On('location-updated')]
    public function handleLocationUpdate($lat, $long)
    {
        $this->new_report['lat'] = $lat;
        $this->new_report['long'] = $long;
        $this->locationSource = (empty($this->locationSource) ? __("user map entry") : $this->locationSource);
        Dialog::toast(__('Location updated.'));
    }

    #[On('manual-location-updated')]
    public function manualMapUpdate()
    {
        $this->locationSource =  __("user map entry") ;
    }

    public function createReport()
    {
        // Check if photoDataUrl contains compressed base64 image
        if (str_starts_with($this->photoDataUrl, 'data:image')) {
            // Use the already compressed image from client-side
            $imageValue = $this->photoDataUrl;
        } else {
            // Fallback: read from file if not already compressed
            $image = Storage::path($this->new_report['image']);
            $data   = base64_encode(file_get_contents($image));
            $mime   = mime_content_type($image);
            $imageValue = "data:$mime;base64,$data";

            // Note: We no longer use MediaHelper::compressBase64Image here
            // as compression should happen client-side
        }

        $data = [
            'title' => $this->new_report['title'],
            'description' => $this->new_report['description'],
            'category' => 'mtb',
            'lat' => (string) $this->new_report['lat'],
            'long' =>(string) $this->new_report['long'],
            'image' => $imageValue,
            'is_urgent' => $this->new_report['is_urgent'],
            'type' => null,
            'email' => SecureStorage::get('user_email'),
            'flagit_user_id' => SecureStorage::get('user_id'),

        ];

        $client = $this->getClient();
        $response_report = $client->postReport($data);
        dd($response_report);
        if(!empty($response_report)) {
            Storage::delete($this->new_report['image']);
            //add report to reports array prepend and cache?


        }
        //NEED TO CLEAN PHOTOS DIRECTORY
    }

    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}

<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use App\Livewire\Traits\ImagePicker;
use App\Livewire\Traits\ReportApi;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Edge\Edge;
use Native\Mobile\Events\Alert\ButtonPressed;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Events\Geolocation\LocationReceived;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\File as MobileFile;
use Native\Mobile\Facades\Geolocation as GeolocationFacade;
use Native\Mobile\Facades\SecureStorage;
use Native\Mobile\Facades\System;

class ReportCreate extends Component
{
    Use Geo, ReportApi, ImagePicker;

    public string $locationSource = '';

    public bool $showMap = true;

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
        $this->new_report['lat'] = SecureStorage::get('current_latitude') ?? 45.9439739740921 ;
        $this->new_report['long'] = SecureStorage::get('current_longitude') ?? -74.20967672863866;

        //for testing non mobile
        if (! System::isMobile() && config('app.env') == 'local') {
            $this->new_report['lat'] = 45.9439739740921  ;
            $this->new_report['long'] = -74.20967672863866;
            $this->photoDataUrl = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAD/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AJV//9k=";
            $this->hasGpsLocation = true;
            $this->locationSource = __("Chrome test location");
            $this->newImage = $this->photoDataUrl;
        }
    }

    public function toggleMap()
    {
        $this->showMap = ! $this->showMap;
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
            $image = Storage::path($this->newImage);
            $data = base64_encode(file_get_contents($image));
            $mime = mime_content_type($image);
            $imageValue = "data:$mime;base64,$data";

            // Note: We no longer use MediaHelper::compressBase64Image here
            // as compression should happen client-side
        }

        $data = [
            'title' => $this->new_report['title'],
            'description' => $this->new_report['description'],
            'category' => 'mtb',
            'lat' => (string) $this->new_report['lat'],
            'long' => (string) $this->new_report['long'],
            'image' => $imageValue,
            'is_urgent' => $this->new_report['is_urgent'],
            'type' => null,
            'email' => auth()->user()->email,
            'flagit_user_id' => auth()->user()->user_id,

        ];

        $client = $this->getClient();
        $response_report = $client->postReport($data);

        if (!empty($response_report)) {
            Storage::delete($this->newImage);
            //add report to reports array prepend and cache?
        }
        $this->redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.reports.create')
            ->layout('components.layouts.app',[
                'title' => 'New report',
                'showEdgeComponents' => false,
                'link_back'=> url('/reports')
            ]);
    }
}

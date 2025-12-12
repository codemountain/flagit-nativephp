<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Geolocation\LocationReceived;
use Native\Mobile\Events\Geolocation\PermissionRequestResult;
use Native\Mobile\Events\Geolocation\PermissionStatusReceived;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\Geolocation as GeolocationFacade;
use Native\Mobile\Facades\SecureStorage;

class Geolocation extends Component
{
    use Geo;

    public bool $showRetry = false;

    #[OnNative(PermissionStatusReceived::class)]
    public function handlePermissionStatus($location, $coarseLocation, $fineLocation)
    {
        $this->isChecking = false;
        $this->result = 'Permission Status :'.$location;
        if($location == 'granted'){
            SecureStorage::set('location_permission', true);
            $this->getLocation();
        } else {
            $this->requestPermission();
            SecureStorage::set('location_permission', false);
        }
    }

    #[OnNative(LocationReceived::class)]
    public function handleLocationReceived($success = null, $latitude = null, $longitude = null, $accuracy = null, $timestamp = null, $provider = null, $error = null)
    {
        $this->isChecking = false;
        if ($success) {
            $this->showRetry = false;
            $this->result = __('Location Received. Thank you.');
            SecureStorage::set('current_latitude', $latitude);
            SecureStorage::set('current_longitude', $longitude);
            SecureStorage::set('current_accuracy', $accuracy);
            Dialog::toast('Location Permissions Received. Thank you.');
            $this->redirect(route('home'));
        } else {
            $this->showRetry = true;
            $this->result = 'Location Error: '.($error ?? 'Unknown error');
            SecureStorage::set('current_latitude', null);
            SecureStorage::set('current_longitude', null);
            SecureStorage::set('current_accuracy', null);
        }
    }

    public function render()
    {
        return view('livewire.geolocation')
            ->layout('components.layouts.app', [
                'title' => 'Geolocation',
            ]);
    }
}

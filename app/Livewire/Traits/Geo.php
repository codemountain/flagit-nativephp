<?php

namespace App\Livewire\Traits;

use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Geolocation\LocationReceived;
use Native\Mobile\Events\Geolocation\PermissionRequestResult;
use Native\Mobile\Events\Geolocation\PermissionStatusReceived;
use Native\Mobile\Facades\Geolocation as GeolocationFacade;
use Native\Mobile\Facades\SecureStorage;

trait Geo
{
    public string $result = '';

    public bool $isChecking = false;


    public function checkPermissions()
    {
        $this->result = 'Checking permissions...';
        GeolocationFacade::checkPermissions();
    }

    public function requestPermission()
    {
        $this->result = 'Requesting permissions...';
        GeolocationFacade::requestPermissions();
    }

    public function getLocation()
    {
        $this->result = 'Getting location...';
        GeolocationFacade::getCurrentPosition(true);
    }

    #[OnNative(PermissionStatusReceived::class)]
    public function handlePermissionStatus($location, $coarseLocation, $fineLocation)
    {
        $this->isChecking = false;
        $this->result = 'Permission Status checkPermissions: Location='.$location.', Coarse='.$coarseLocation.', Fine='.$fineLocation;
        if($location == 'granted'){
            SecureStorage::set('location_permission', true);
        } else {
            $this->requestPermission();
            SecureStorage::set('location_permission', false);
        }
    }

    #[OnNative(PermissionRequestResult::class)]
    public function handlePermissionRequest($location, $coarseLocation, $fineLocation, $message = null, $needsSettings = null)
    {
        if ($location === 'permanently_denied') {
            $this->result = 'Permissions permanently denied. '.($message ?? 'Please enable location in Settings.');
            SecureStorage::set('location_permission', false);
        } else {
            $this->getLocation();
            $this->result = 'Permission Request Result from requestPermissions: Location='.$location.', Coarse='.$coarseLocation.', Fine='.$fineLocation;
            SecureStorage::set('location_permission', true);
        }
    }

    #[OnNative(LocationReceived::class)]
    public function handleLocationReceived($success = null, $latitude = null, $longitude = null, $accuracy = null, $timestamp = null, $provider = null, $error = null)
    {
        if ($success) {
            $this->result = 'Location from getLocation: '.$latitude.', '.$longitude.' (±'.$accuracy.'m) via '.$provider;
            SecureStorage::set('current_latitude', $latitude);
            SecureStorage::set('current_longitude', $longitude);
            SecureStorage::set('current_accuracy', $accuracy);
        } else {
            $this->result = 'Location Error: '.($error ?? 'Unknown error');
            SecureStorage::set('current_latitude', null);
            SecureStorage::set('current_longitude', null);
            SecureStorage::set('current_accuracy', null);
        }
    }

}

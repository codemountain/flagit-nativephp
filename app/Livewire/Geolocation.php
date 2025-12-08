<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Geolocation\LocationReceived;
use Native\Mobile\Events\Geolocation\PermissionRequestResult;
use Native\Mobile\Events\Geolocation\PermissionStatusReceived;
use Native\Mobile\Facades\Geolocation as GeolocationFacade;
use Native\Mobile\Facades\SecureStorage;

class Geolocation extends Component
{
    use Geo;


    public function render()
    {
        return view('livewire.geolocation')
            ->layout('components.layouts.app', [
                'title' => 'Geolocation',
            ]);
    }
}

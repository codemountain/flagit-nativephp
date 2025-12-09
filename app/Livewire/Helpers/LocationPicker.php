<?php

namespace App\Livewire\Helpers;

use App\Livewire\Traits\Geo;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Facades\SecureStorage;

class LocationPicker extends Component
{

    use Geo;
    public $lat;
    public $long;
    public $class = 'w-full h-40';
    public $label = null;
    public $os = null;

    public function mount($lat = null, $long = null, $class = null, $label = null)
    {
        // Initialize HasGeo trait
        $this->os = SecureStorage::get('device_os');

        // Set initial coordinates with proper fallbacks
        $this->js('console.log("🔧 MOUNT: Starting mount with lat:", '.json_encode($lat).', "long:", '.json_encode($long).')');

        if ($lat !== null && $long !== null) {
            // Use provided coordinates
            $this->lat = $lat;
            $this->long = $long;
            $this->js('console.log("🔧 MOUNT: Using provided coordinates:", '.$lat.', '.$long.')');
        } else {
            // Debug cache state
            $cachedLocation = \Illuminate\Support\Facades\Cache::get('last_user_location');
            $this->js('console.log("🔧 MOUNT: Raw cache contents:", '.json_encode($cachedLocation ?: 'null').')');

            // Use cached location from HasGeo trait
            $defaultLocation = $this->getDefaultLocation();
            $this->js('console.log("🔧 MOUNT: getDefaultLocation() returned:", '.json_encode($defaultLocation).')');

            $this->lat = $defaultLocation['latitude'];
            $this->long = $defaultLocation['longitude'];
            $this->js('console.log("🔧 MOUNT: Final coordinates set to:", '.$this->lat.', '.$this->long.')');
        }

        $this->js('console.log("LocationPicker mounted with lat: '.$this->lat.', long: '.$this->long.'");');

        // Override default class if provided
        if ($class) {
            $this->class = $class;
        }

        $this->label = $label;
    }

    public function updateLocation($lat, $lng)
    {
        $this->lat = $lat;
        $this->long = $lng; // Store as 'long' property but accept 'lng' parameter

        $this->dispatch('location-updated',
            lat: $lat,
            long: $lng
        );
    }

    public function manualMapUpdate()
    {
        $this->dispatch('manual-location-updated');
    }
    /**
     * Update location from map interaction and sync with user location
     */
    public function updateLocationFromMap($lat, $lng)
    {
//        ray('LocationPicker: updateLocationFromMap called with: '.$lat.', '.$lng);
        $this->js('console.log("🔧 PHP: updateLocationFromMap called with: '.$lat.', '.$lng.'")');

        // Update the picker's coordinates (convert lng to long for internal use)
        $this->updateLocation($lat, $lng);

    }

    /**
     * Handle center-map-on-location event from HasGeo trait
     */
    #[On('center-map-on-location')]
    public function handleCenterMapOnLocation($data)
    {
        // Extract data from the event payload
        $lat = $data['lat'] ?? null;
        $lng = $data['lng'] ?? null;
        $componentId = $data['componentId'] ?? null;

        // Only handle if this is for our component
        if ($componentId === $this->getId() && $lat && $lng) {
            // Update our coordinates
            $this->lat = $lat;
            $this->long = $lng;

            // Dispatch JavaScript event to center the map
            $this->dispatch('move-map-to-location',
                componentId: $this->getId(),
                lat: $lat,
                lng: $lng
            );
        }
    }

    public function requestUserLocation()
    {
        // Add debugging
        $this->js('console.log("PHP: requestUserLocation called');

        // Try getting location directly - this should trigger permission request if needed
        $this->js('console.log("PHP: Calling Geolocation::getCurrentPosition(true)")');
        $this->getLocation();
    }

    public function render()
    {
        return view('livewire.helpers.location-picker');
    }
}

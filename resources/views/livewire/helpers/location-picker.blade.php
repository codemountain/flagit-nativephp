<div>
    @if($label)
        <flux:label>{{ $label }}</flux:label>
    @endif

    <div class="relative {{ $class }}">
        <!-- Map container -->
        <div
            id="location-picker-{{ $this->getId() }}"
            wire:ignore
            class="w-full h-full border border-zinc-200 dark:border-zinc-700 rounded-lg"
        ></div>

        <!-- Fixed center marker - positioned above the map -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-[1000]">
            <div class="w-8 h-8 rounded-full border-2 border-accent shadow-lg flex items-center justify-center opacity-50">
                <flux:icon.viewfinder-circle
                    size="6"
                    class="text-accent"/>
            </div>
        </div>
    </div>

    <!-- Display current coordinates (optional, for debugging) -->
{{--    @if(config('app.debug'))--}}
{{--        <div class="text-xs text-gray-500 mt-1">--}}
{{--            Lat: {{ number_format($lat, 6) }}, Long: {{ number_format($long, 6) }} | OS: {{ $os }}--}}
{{--        </div>--}}

{{--    @endif--}}
</div>

<script>
    const mapId = 'location-picker-{{ $this->getId() }}';
    const os = '{{ $os ?? 'android' }}';
    const componentId = '{{ $this->getId() }}';

    function initializeLocationPicker() {
        console.log('Initializing location picker:', mapId);

        const mapElement = document.getElementById(mapId);

        if (!mapElement) {
            console.error('Map element not found:', mapId);
            return;
        }

        if (mapElement.hasAttribute('data-map-initialized')) {
            console.log('Map already initialized:', mapId);
            return;
        }

        try {
            // Mark as initialized to prevent duplicate initialization
            mapElement.setAttribute('data-map-initialized', 'true');

            console.log('Creating map with coordinates:', {{ $lat }}, {{ $long }});

            // Initialize Mapbox GL JS map
            mapboxgl.accessToken = '{{ config('services.mapbox.token') }}';
            const map = new mapboxgl.Map({
                container: mapId,
                center: [{{ $long }}, {{ $lat }}], // [lng, lat] format for Mapbox
                zoom: 15,
                style: '{{ config('services.mapbox.style') }}'
            });

            // Add navigation controls
            const nav = new mapboxgl.NavigationControl();
            map.addControl(nav, 'top-right');

            // ✨ Use the unified factory to create geolocate control
            if (window.MapboxGeolocateControlFactory) {
                const geolocateControl = MapboxGeolocateControlFactory.create(os, {
                    componentId: componentId,
                    // Location picker specific options - don't show user location visually
                    trackUserLocation: false,
                    showUserHeading: false,
                    showAccuracyCircle: false,
                    showUserLocation: false,
                    onLocationReceived: function(lat, lng) {
                        console.log('🎯 LocationPicker: Location received from unified geolocate control:', lat, lng);

                        // Center map on user location
                        map.setCenter([lng, lat]);

                        // Update the component's location (method expects lat, lng)
                        console.log('🎯 LocationPicker: Calling updateLocationFromMap with:', lat, lng);
                        @this.call('updateLocationFromMap', lat, lng);
                    },
                    onError: function(error) {
                        console.error('Unified geolocate error:', error);
                        alert('Location Error: ' + error.message);
                    }
                });

                map.addControl(geolocateControl, 'top-right');
                console.log('Unified geolocate control added to location picker');
            } else {
                console.error('MapboxGeolocateControlFactory not found');
            }

            console.log('Map created successfully');
            //dispatch map-loaded event
            document.dispatchEvent(new CustomEvent('map-loaded', {
                detail: {
                    mapId: 'location-picker'
                }
            }));

            // Update location when map stops moving
            map.on('moveend', function() {
                const center = map.getCenter();
                console.log('Map moved to:', center.lat, center.lng);

                // Update the component's location (method expects lat, lng)
                @this.call('updateLocation', center.lat, center.lng);
            });

            // Example of a MapTouchEvent of type "touch"
            map.on('touchstart', (e) => {
                console.log("touchstart");
                @this.call('manualMapUpdate');
            });

            // Store map reference for potential cleanup
            mapElement._mapboxMap = map;

        } catch (error) {
            console.error('Error initializing map:', error);
            mapElement.removeAttribute('data-map-initialized');
        }
    }

    function waitForMapbox(callback, maxAttempts = 50) {
        let attempts = 0;

        function check() {
            attempts++;

            if (typeof mapboxgl !== 'undefined') {
                console.log('Mapbox GL JS loaded after', attempts, 'attempts');
                callback();
            } else if (attempts < maxAttempts) {
                console.log('Waiting for Mapbox GL JS... attempt', attempts);
                setTimeout(check, 200);
            } else {
                console.error('Mapbox GL JS failed to load after', maxAttempts, 'attempts');
            }
        }

        check();
    }

    // Wait for Mapbox GL JS to be available, then initialize
    waitForMapbox(initializeLocationPicker);

    // Listen for move-map-to-location events from Livewire
    document.addEventListener('livewire:init', function() {
        // Listen for move-map-to-location events from Livewire
        Livewire.on('move-map-to-location', function(data) {
            if (data.componentId === componentId) {
                console.log('Livewire move-map-to-location:', data);

                const mapElement = document.getElementById(mapId);
                if (mapElement && mapElement._mapboxMap) {
                    mapElement._mapboxMap.setCenter([data.lng, data.lat]);
                }
            }
        });

        // Listen for location errors
        Livewire.on('location-error', function(data) {
            console.error('Location error:', data.message);
            alert('Location Error: ' + data.message);
        });
    });
</script>


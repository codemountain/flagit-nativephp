<div>
    @if(!empty($report) && !empty($report->id))
   <div id="report-location" class="hidegeolocatebutton w-full h-[100vh] m-0! p0!" wire:ignore></div>


{{--    <flux:icon.chevron-down class="z-50 absolute top-6 left-6 p-4 size-14 bg-black/80 rounded-full text-white cursor-pointer" x-on:click="$flux.modal('report-map').close()" />--}}

<script data-navigate-track>
    function initializeMap() {
            console.log('report currently available: ', {{$report->lat}});
            // Always center on the report location for details view
            const reportLat = {{$report->lat}};
            const reportLong = {{$report->long}};
            // console.log('Centering map on report location:', reportLat, reportLong);
            mapboxgl.accessToken = '{{config('services.mapbox.token')}}';
            const map = new mapboxgl.Map({
                container: 'report-location', // container ID
                center: [reportLong, reportLat], // center on report location
                zoom: 15, // starting zoom
                style: '{{config('services.mapbox.style')}}'
            });

            const el = document.createElement('div');
            const width = 30;
            const height = 38;
            const marker = '{{asset('icons/markers/'.$report->status.'.svg')}}';
            el.className = 'marker';
            el.style.backgroundImage = `url(${marker})`;
            el.style.width = `${width}px`;
            el.style.height = `${height}px`;
            el.style.backgroundSize = '100%';

            // make a marker for each feature and add to the map
            const markerElement = new mapboxgl.Marker(el)
                .setLngLat([{{$report->long}}, {{$report->lat}}])
                .addTo(map);

            //code from step 8 will go here
            const nav = new mapboxgl.NavigationControl();
            map.addControl(nav, 'top-right');

            // ✨ Use the unified factory to create geolocate control
            if (window.MapboxGeolocateControlFactory) {
                const os = {{$os}};
                const componentId = '{{ $this->getId() }}';
                //hidding the geolocate button with CSS app.css
                const geolocateControl = MapboxGeolocateControlFactory.create(os, {
                    componentId: componentId,
                    // Report map specific options - show user location visually
                    trackUserLocation: true,
                    showUserHeading: true,
                    showAccuracyCircle: true,
                    showUserLocation: true,
                    onLocationReceived: function (lat, lng) {
                        console.log('Location received in unified report map control:', lat, lng);
                        // Center map on user location
                        map.setCenter([lng, lat]);
                    },
                    onError: function (error) {
                        console.error('Unified geolocate error in report map:', error);
                        alert('Location Error: ' + error.message);
                    }
                });

                map.addControl(geolocateControl, 'top-right');
                console.log('Unified geolocate control added to report map');
            } else {
                console.error('MapboxGeolocateControlFactory not found');
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
    waitForMapbox(initializeMap);
    // Listen for move-map-to-location events from Livewire
    document.addEventListener('livewire:init', function() {
        // Listen for move-map-to-location events from Livewire
        // Livewire.on('move-map-to-location', function(data) {
        //     if (data.componentId === componentId) {
        //         console.log('Livewire move-map-to-location:', data);
        //
        //         const mapElement = document.getElementById(mapId);
        //         if (mapElement && mapElement._mapboxMap) {
        //             mapElement._mapboxMap.setCenter([data.lng, data.lat]);
        //         }
        //     }
        // });

        // Listen for location errors
        Livewire.on('location-error', function(data) {
            console.error('Location error:', data.message);
            alert('Location Error: ' + data.message);
        });
    });
</script>
@else
    No report valid
@endif


</div>

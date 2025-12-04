<div>
{{--    @if(!empty($report) && !empty($report->id))--}}
        <div class="block w-full">
            <!-- First section - carousel (full width on mobile, half width on desktop) -->
            <div class="w-full mb-6">

                <!-- Mobile title -->

                <div class="flex justify-between items-center -mt-1">
                    <div class="flex justify-start items-center gap-2">
                        <flux:button  href="{{route('home')}}" wire:navigate
                                      variant="filled">
                            <flux:icon.arrow-left class="h-8!" />
                        </flux:button>
                        <flux:heading size="xl" level="1">
                            <div class="mt-2">Loup Garou</div>
                        </flux:heading>
                    </div>
                    <flux:icon.marker-status variant="submitted" size="6" />
                </div>
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-xl">Nordique</div>
                    <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75" >2 days ago</flux:badge>
                </div>
                <div class="text-lg text-zinc-500 dark:text-zinc-400">Roche dangeureuse</div>
                <!-- Carousel container -->
                <div class="flex items-center gap-2 justify-end">
                    <div class="relative bg-primary w-full p-0 rounded-lg mt-4">

                        <img
                            src="https://placehold.co/400x600/png"
                            class="w-full h-full object-cover relative rounded-t-2xl"
                        />

{{--                        @if(!$embedded)--}}
                            <div class="absolute bottom-2 right-2">
                                <flux:button
                                    wire:click="openMap"
                                    class="w-16 h-16 opacity-70"
                                    variant="primary"
                                >
                                    <flux:icon.map class="size-8 text-white dark:text-black" variant="solid" />
                                </flux:button>

                            </div>

{{--                        @endif--}}

                    </div>
                </div>

                <div class="p-6 bg-zinc-400 dark:bg-zinc-700 mt-0 rounded-b-2xl relative">
                    <div class="text-sm text-zinc-50 dark:text-zinc-200 mr-24">
                        Lorem ipsum description
                    </div>
{{--                    @if(!$embedded)--}}
                        <div class="absolute bottom-2 right-2">
                            <flux:dropdown gap="2" position="left">
                                <flux:button
                                    class="w-16 h-13 opacity-70 bg-transparent! border-0!"
                                    variant="outline"
                                >
                                    <flux:icon.ellipsis-horizontal-circle
                                        class="size-10 text-zinc-700 dark:text-white" variant="solid" />
                                </flux:button>
                                <flux:popover class="-mt-2 bg-zinc-700 border border-zinc-600 rounded-lg opacity-70">
                                    <flux:button
                                        wire:click="seeNotes"
                                        class="relative mr-4"
                                        icon="chat-bubble-bottom-center-text"
                                        iconSize=""
                                    >
                                    </flux:button>
                                    <flux:button
                                        wire:click="openFixReport"
                                        class="relative mr-4"
                                        icon="wrench-screwdriver"
                                        iconSize=""
                                    >
                                    </flux:button>
                                    <flux:button
                                        wire:click="openWorklogs"
                                        class="relative mr-4"
                                        icon="clock"
                                        iconSize=""
                                    >
                                    </flux:button>
                                    <flux:button
                                        wire:click="openEditReport"
                                        class="relative"
                                        icon="pencil"
                                        iconSize=""
                                        disabled
                                    >
                                    </flux:button>
                                </flux:popover>
                            </flux:dropdown>

{{--                            @if($report->notes->count() > 0)--}}
                                <div class="absolute -top-2 -left-2 bg-amber-600 rounded-full w-5 h-5 flex items-center justify-center text-white text-xs">100</div>
{{--                            @endif--}}
                        </div>
{{--                    @endif--}}
                </div>
                <div class="py-6">
{{--                    @if($report->category_names)--}}
{{--                        <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4">--}}
{{--                            <flux:heading size="sm text-bold">{{__('Categories')}}</flux:heading>--}}
{{--                            @foreach(explode(',', $report->category_names) as $item)--}}
{{--                                <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    @if($report->skill_names)--}}
{{--                        <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">--}}
{{--                        <flux:heading size="sm text-bold">{{__('Skills')}}</flux:heading>--}}
{{--                        @foreach(explode(',', $report->skill_names) as $item)--}}
{{--                            <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>--}}
{{--                        @endforeach--}}
{{--                </div>--}}
{{--                @endif--}}
{{--                @if($report->material_names)--}}
{{--                    <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">--}}
{{--                    <flux:heading size="sm text-bold">{{__('Materials')}}</flux:heading>--}}
{{--                    @foreach(explode(',', $report->material_names) as $item)--}}
{{--                        <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>--}}
{{--                    @endforeach--}}
{{--            </div>--}}
{{--            @endif--}}
{{--            @if($report->equipment_names)--}}
{{--                <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">--}}
{{--                <flux:heading size="sm text-bold">{{__('Materials')}}</flux:heading>--}}
{{--                @foreach(explode(',', $report->equipment_names) as $item)--}}
{{--                    <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>--}}
{{--                @endforeach--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @if($report->task_names)--}}
{{--        <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">--}}
{{--        <flux:heading size="sm text-bold">{{__('Tasks')}}</flux:heading>--}}
{{--        @foreach(explode(',', $report->task_names) as $item)--}}
{{--            <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>--}}
{{--        @endforeach--}}
{{--</div>--}}
{{--@endif--}}

<div class="w-full text-sm text-right text-zinc-500 dark:text-zinc-400 mt-4 pr-2 italic">
    {{__('Submitted by')}}: date goes here<br> Bob smith
{{--    @if(!empty($report->createdByUser))--}}
{{--        {{$report->createdByUser->fullname}} - {{$report->createdByUser->email}}--}}
{{--    @endif--}}
</div>


</div>
<flux:modal name="report-worklogs" class="w-full h-[87%] border border-neutral-content rounded-t-2xl p-0!" variant="flyout" position="bottom" closable="false">
{{--    @if($worklogs)--}}
{{--        <div class="w-full mb-6 mt-4 p-6">--}}
{{--            <flux:heading size="xl" level="1">{{__('Worklogs')}}</flux:heading>--}}
{{--            @if(!empty($worklogs))--}}
{{--                <div class="mt-4 text-sm grid grid-cols-2 gap-2 bg-lime-700/20  rounded-xl p-4">--}}
{{--                    @php $totalDuration = 0; @endphp--}}
{{--                    @foreach($worklogs as $log)--}}
{{--                        <div>{{$log['performer_name']}}--}}
{{--                            <br>--}}
{{--                            <flux:tooltip content="{{$log['description']}}">--}}
{{--                                {{Str::limit($log['description'],20,'...')}}--}}
{{--                            </flux:tooltip>--}}
{{--                        </div>--}}

{{--                        <div class="flex items-center justify-end">--}}
{{--                            @if($log['duration'] < 60)--}}
{{--                                {{$log['duration']}} {{__('min.')}}--}}
{{--                            @else--}}
{{--                                {{round($log['duration']/60,1)}} {{__('hours.')}}--}}
{{--                            @endif--}}
{{--                            <br>--}}
{{--                            {{$log['performed_at']}}--}}
{{--                        </div>--}}
{{--                        @php $totalDuration += $log['duration']; @endphp--}}
{{--                    @endforeach--}}

{{--                </div>--}}
{{--                <div class="mt-4 text-sm w-full bg-lime-700/20  rounded-xl p-4">--}}
{{--                    <div class="flex justify-end text-right">{{__('Total Duration')}}: {{$totalDuration}} {{__('min.')}} | {{ round($totalDuration/60,1) }} {{__('hours.')}}</div>--}}
{{--                </div>--}}
{{--            @else--}}
{{--                <div class="mt-4 text-sm grid grid-cols-1 gap-2 bg-zinc-700/20  rounded-xl p-4">--}}
{{--                    {{__('No worklogs found.')}}--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endif--}}
    Worklogs here
</flux:modal>
<flux:modal name="report-fixit" class="w-full h-[87%] border border-neutral-content rounded-t-2xl p-0!" variant="flyout" position="bottom" closable="false">
{{--    @if($report)--}}
{{--        <livewire:reports.fixit :report="$report" />--}}
{{--    @endif--}}
    Fix it here
</flux:modal>

<flux:modal name="report-map" class="w-full h-[80%] border border-neutral-content rounded-t-2xl p-0!" variant="flyout" position="bottom" closable="false">
    <div id="report-location" wire:ignore class="w-full h-[100%] mt-0!"></div>
    <flux:icon.chevron-down class="z-50 absolute top-6 left-6 p-4 size-14 bg-black/80 rounded-full text-white cursor-pointer" x-on:click="$flux.modal('report-map').close()" />
</flux:modal>
<script data-navigate-track>

    // Add global debug listeners for user-location-updated events (multiple formats)
    document.addEventListener('livewire:user-location-updated', function(event) {
        console.log('Global listener (livewire:): Received user-location-updated event:', event.detail);
    });

    document.addEventListener('user-location-updated', function(event) {
        console.log('Global listener (direct): Received user-location-updated event:', event.detail);
    });

    window.addEventListener('user-location-updated', function(event) {
        console.log('Global listener (window): Received user-location-updated event:', event.detail);
    });

    document.addEventListener('open-map', function(event) {
        console.log('Received refresh-map event:', event.detail);

        // Always center on the report location for details view
        const reportLat = 40;
        const reportLong = -75;
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
        const marker = '{{asset('icons/markers/submitted.svg')}}';
        el.className = 'marker';
        el.style.backgroundImage = `url(${marker})`;
        el.style.width = `${width}px`;
        el.style.height = `${height}px`;
        el.style.backgroundSize = '100%';

        // make a marker for each feature and add to the map
        const markerElement = new mapboxgl.Marker(el)
            .setLngLat([-75, 40])
            .addTo(map);

        //code from step 8 will go here
        const nav = new mapboxgl.NavigationControl();
        map.addControl(nav, 'top-right');

        // ✨ Use the unified factory to create geolocate control
        if (window.MapboxGeolocateControlFactory) {
            const os = 'android';
            const componentId = '{{ $this->getId() }}';
            const geolocateControl = MapboxGeolocateControlFactory.create(os, {
                componentId: componentId,
                // Report map specific options - show user location visually
                trackUserLocation: true,
                showUserHeading: true,
                showAccuracyCircle: true,
                showUserLocation: true,
                onLocationReceived: function(lat, lng) {
                    console.log('Location received in unified report map control:', lat, lng);
                    // Center map on user location
                    map.setCenter([lng, lat]);
                },
                onError: function(error) {
                    console.error('Unified geolocate error in report map:', error);
                    alert('Location Error: ' + error.message);
                }
            });

            map.addControl(geolocateControl, 'top-right');
            console.log('Unified geolocate control added to report map');
        } else {
            console.error('MapboxGeolocateControlFactory not found');
        }

    });
</script>
{{--@else--}}
{{--    No report valid--}}
{{--    @endif--}}


    </div>

    </div>

</div>

<div class="space-y-6">
    <!-- Main Content Area with Horizontal Padding -->
    <form wire:submit="createReport" class="">
        @if(!empty($new_report['lat']) && !empty($new_report['long']))
        <div class="h-[100vh] w-full">
             <livewire:helpers.location-picker
                 :lat="$new_report['lat'] ?? null"
                 :long="$new_report['long'] ?? null"
                 label="{{__('Location')}}"
                 class="h-80 z-10"
             />
        </div>
        @endif

        <div class="flex justify-between gap-2 relative mb-4">
            <!-- Image Picker - First field -->
            <div
                wire:click="getImage"
                class="h-64 w-full flex justify-center rounded-lg border border-zinc-200 dark:border-zinc-700 p-0 items-center text-center bg-zinc-50 dark:bg-zinc-600">
                @if(!empty($photoDataUrl))
                    <img src="{{$photoDataUrl}}" class="w-full h-full object-cover relative" alt="preview" id="reportimg"/>
                @else
                    <flux:button  class="w-16 h-16 rounded-full border-0 bg-zinc-50! dark:bg-zinc-600!">
                        <flux:icon.camera class="size-20 text-zinc-400 dark:text-zinc-500" />
                    </flux:button>
                @endif

            </div>
            @if(!empty($photoDataUrl))
            <div class="absolute bottom-0 right-0 p-2 bg-amber-700/70 rounded-tl-xl px-2">{{$photoGeoStatus}}</div>
            @endif
        </div>
{{--        <div class="bg-zinc-500/20 p-4 rounded-2xl">--}}
{{--            {{ print_r($new_report,true) }}--}}
{{--        </div>--}}
        @if(!empty($photoDataUrl) && !$hasGpsLocation)
            <flux:callout icon="map-pin" color="red" class="mb-4">
                <flux:callout.heading>{{__('No GPS Data available on image')}}</flux:callout.heading>
                <x-slot name="actions">
                    <flux:button class="w-full -ml-4" wire:click="getLocation">{{__('Use current')}}</flux:button>
                </x-slot>
            </flux:callout>
        @endif
        <div class="w-full gap-4 space-y-4 relative">
            <flux:input
                label="{{__('Title')}}"
                placeholder="{{__('Fallen tree, blocked drainage')}}..."
                wire:model.live="new_report.title"
                class="mobile"
                required
            />
            <flux:field variant="inline" class="w-1/3 absolute top-0 right-0 mobile">
                <flux:label>Is urgent?</flux:label>
                <flux:switch wire:model="new_report.is_urgent" />
                <flux:error name="is_urgent" />
            </flux:field>
            <flux:textarea
                label="{{__('Description')}}"
                wire:model.live="new_report.description"
                placeholder="{{__(' ')}}..."
                rows="2"
            />

        </div>

        <div class="h-4 w-full"></div>
        {{--                <div class="fixed bottom-0 p-4 h-32 left-0 right-0 z-20 bg-white border-t border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700 lg:hidden shadow-lg" style="padding-bottom: env(safe-area-inset-bottom, 0px); box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1), 0 -2px 4px -2px rgb(0 0 0 / 0.1);">--}}
        <div class="p-4 h-32 left-0 right-0 z-20 bg-white  dark:bg-zinc-900  lg:hidden"  >
            <div class="flex items-center justify-between gap-4  px-4 py-4">

                <flux:button
                    class="w-full mobile"
                    variant="outline"
                    href="{{route('home')}}"
                    wire:navigate
                >
                    {{__('Cancel')}}
                </flux:button>

                @if(!empty($photoDataUrl) && $hasGpsLocation && !empty($new_report['title']))
                <flux:button type="submit"
                             class="w-full bg-amber-700! mobile"
                            variant="primary"
                >
                    {{__('Send Report')}}</flux:button>
                @endif
            </div>
        </div>

    </form>




    <flux:modal name="image-method" class="w-full h-50 border border-neutral-content rounded-t-2xl" variant="flyout" position="bottom">
        <div class="flex flex-col gap-4 p-4 pb-0 mb-0">
            <flux:button wire:click="getImageFromCamera" class="w-full mobile" variant="primary">{{__('Take Photo')}}</flux:button>
            <flux:button wire:click="getImageFromLibrary" class="w-full mobile">{{__('Choose from Library')}}</flux:button>
        </div>
    </flux:modal>

    <script>
        document.addEventListener('exif-new-image', function(event) {
            console.log('📸 Event received, looking for image...');
            console.log('🔍 Checking exifr availability:', typeof window.exifr);
            console.log('🔍 Checking global exifr:', typeof exifr);

            // Try both window.exifr and global exifr
            const exifrLib = exifr || (typeof exifr !== 'undefined' ? exifr : null);

            if (!exifrLib) {
                console.error('❌ exifr library not found');
                console.log('🔍 Available on window:', Object.keys(window).filter(k => k.toLowerCase().includes('exif')));
                return;
            }

            console.log('✅ exifr found:', exifrLib);

            // Wait for DOM to update, then find the image
            setTimeout(() => {
                const img = document.querySelector('#reportimg');
                console.log('🖼️ Image element:', img);
                console.log('📊 Image src exists:', img ? !!img.src : 'no img');

                if (img && img.src) {
                    console.log('🔄 Extracting GPS from image src...');

                    // Use the image src directly with exifr
                    fetch(img.src)
                        .then(response => response.blob())
                        .then(blob => {
                            console.log('🔄 Blob created, extracting GPS...');
                            return exifrLib.gps(blob);
                        })
                        .then(gpsData => {
                            console.log('🗺️ GPS Result:', gpsData);
                            @this.call('handleImageGPS', gpsData);
                        })
                        .catch(error => {
                            @this.call('handleImageGPSError',error);
                            console.error('❌ GPS extraction error:', error);
                        });
                } else {
                    console.log('❌ No image element or src found');
                }
            }, 200); // Longer delay to ensure DOM is updated
        });

    </script>

</div>


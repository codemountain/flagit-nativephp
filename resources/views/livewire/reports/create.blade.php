<div class="space-y-6">
    <!-- Main Content Area with Horizontal Padding -->
    <form wire:submit="createReport" class="">


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
            @if(!empty($photoDataUrl) && empty($locationSource))
            <div class="absolute bottom-0 right-0 p-2 bg-amber-700/70 rounded-tl-xl px-2">{{$photoGeoStatus}}</div>
            @endif
            @if(!empty($locationSource))
                <div class="absolute bottom-0 right-0 p-2 w-full" x-on:click="$flux.modal('map-location').show()">
                <flux:callout icon="map" color="green" class="mb-0">
                    <flux:callout.heading>
                        {{__('Got Location from :source',['source'=>$locationSource])}}
                    </flux:callout.heading>
{{--                    <x-slot name="actions">--}}
{{--                        <flux:button class="w-full mr-6"--}}
{{--                                     variant="outline"--}}
{{--                                     icon="map"--}}
{{--                                     x-on:click="$flux.modal('map-location').show()" >--}}
{{--                            {{__('View map')}}--}}
{{--                        </flux:button>--}}
{{--                    </x-slot>--}}
                </flux:callout>
                </div>
            @endif
        </div>
{{--        <div class="bg-zinc-500/20 p-4 rounded-2xl">--}}
{{--            {{ print_r($new_report,true) }}--}}
{{--        </div>--}}

       @if(!empty($photoDataUrl) && !empty($locationSource))
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
        @endif

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


    <flux:modal name="map-location" class="w-full h-[90vh] border border-neutral-content rounded-t-2xl p-0!" variant="flyout" position="bottom" closable="false">
            @if(!empty($new_report['lat']) && !empty($new_report['long']))
                <div id="report-location" class="w-full h-full mt-0!">
                    <livewire:helpers.location-picker
                        :lat="$new_report['lat'] ?? null"
                        :long="$new_report['long'] ?? null"
                        class="h-[90vh] z-10"
                    />

                </div>
            @else
                <flux:callout variant="danger" icon="x-circle" heading="Cannot get location from Mobile or Image." />
           @endif

        <flux:icon.chevron-down class="z-50 absolute top-6 left-6 p-4 size-14 bg-black/80 rounded-full text-white cursor-pointer" x-on:click="$flux.modal('map-location').close()" />
    </flux:modal>


    <script>
        // Image EXIF extraction and compression handler
        document.addEventListener('exif-new-image', async function(event) {
            console.log('📸 Event received, processing image...');

            // Wait for DOM to update
            await new Promise(resolve => setTimeout(resolve, 200));

            const img = document.querySelector('#reportimg');
            if (!img || !img.src) {
                console.error('❌ No image element or src found');
                return;
            }

            try {
                // Step 1: Fetch the original image
                console.log('🔄 Fetching original image...');
                const response = await fetch(img.src);
                const originalBlob = await response.blob();

                // Step 2: Extract GPS data from the ORIGINAL image (before compression)
                const exifrLib = window.exifr || (typeof exifr !== 'undefined' ? exifr : null);

                if (!exifrLib) {
                    console.error('❌ exifr library not found');
                    return;
                }

                console.log('🔄 Extracting GPS data from original image...');
                const gpsData = await exifrLib.gps(originalBlob);
                console.log('🗺️ GPS Result:', gpsData);

                // Send GPS data to Livewire component
                @this.call('handleImageGPS', gpsData);

                // Step 3: Compress the image using Canvas API (after EXIF extraction)
                if (window.ImageCompressor) {
                    console.log('🗜️ Compressing image with Canvas API...');
                    const compressionResult = await window.ImageCompressor.compress(originalBlob, {
                        maxWidth: 1920,
                        maxHeight: 1920,
                        quality: 0.85,
                        maxSizeMB: 2  // 2MB max size
                    });

                    if (!compressionResult.success) {
                        console.error('❌ Compression failed:', compressionResult.error);
                        @this.call('handleImageCompressionError', compressionResult.error);
                        return;
                    }

                    console.log('✅ Compression successful');

                    // Convert compressed blob to base64 for storage
                    const compressedBase64 = await window.ImageCompressor.blobToBase64(compressionResult.blob);

                    // Update the image in Livewire component with compressed version
                    @this.set('photoDataUrl', compressedBase64);
                } else {
                    console.warn('⚠️ ImageCompressor not available, using original image');
                    // If compressor not available, keep the original image
                }

            } catch (error) {
                console.error('❌ Error processing image:', error);

                // Determine if it's a compression or EXIF error
                if (error.message && error.message.includes('GPS')) {
                    @this.call('handleImageGPSError', error.message);
                } else if (error.message && error.message.includes('compress')) {
                    @this.call('handleImageCompressionError', error.message || 'Image compression failed');
                } else {
                    // Generic error - likely EXIF related since that happens first
                    @this.call('handleImageGPSError', error.message || 'Failed to extract image data');
                }
            }
        });
    </script>

</div>


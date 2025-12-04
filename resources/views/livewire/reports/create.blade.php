<div class="block w-full">
    <!-- First section - carousel (full width on mobile, half width on desktop) -->
    <div class="w-full mb-6">
        <form wire:submit="createReport" class="">
            <div class="flex justify-between gap-2 mb-8">
                <!-- Image Picker - First field -->
                <div
                    wire:click="getImage"
                    class="h-96! w-full flex justify-center rounded-lg border border-zinc-200 dark:border-zinc-700 p-0 items-center text-center bg-zinc-50">
                    {{--                    @if(!empty($previewImage))--}}
                    {{--                        <img src="{{$previewImage}}" class="w-full h-full object-cover relative" alt="preview" id="reportimg"/>--}}
                    {{--                    @else--}}
                    <flux:button  class="w-16 h-16 rounded-full border-0 bg-zinc-50!">
                        <flux:icon.camera class="size-20 text-zinc-400" />
                    </flux:button>
                    {{--                    @endif--}}

                </div>
                {{--                <div class="w-[100%]">--}}
                {{--                    <flux:radio.group variant="cards" :indicator="false" class="grid grid-cols-1" wire:model.live="new_report.type">--}}
                {{--                        <flux:radio value="trail" label="Trail"  description="ex: Drainage, erosion..." @class(["bg-amber-400/30! border-amber-400!" => $new_report['type'] == 'trail'])/>--}}
                {{--                        <flux:radio value="vegetation"  label="Vegetation"  description="ex: Tree, branches..." @class(["bg-amber-400/30! border-amber-400!" => $new_report['type']  == 'vegetation'])/>--}}
                {{--                        <flux:radio value="feature" label="Feature" description="ex: Bridge, jump..." @class(["bg-amber-400/30! border-amber-400!" => $new_report['type']  == 'feature'])/>--}}
                {{--                    </flux:radio.group>--}}
                {{--                </div>--}}
            </div>


            <div class="w-full gap-4 space-y-4 relative mt-8">
                <flux:input
                    label="{{__('Title')}}"
                    class="mobile"
                    placeholder="{{__('Fallen tree, blocked drainage')}}..."
                    wire:model="new_report.title"
                    required
                />
                <flux:field variant="inline" class="w-1/3 absolute top-0 right-2">
                    <flux:label>Is urgent?</flux:label>
                    <flux:switch wire:model="new_report.is_urgent" class="dark:bg-amber-700!"/>
                </flux:field>
                <flux:textarea
                    label="{{__('Description')}}"
                    wire:model="new_report.description"
                    placeholder="{{__(' ')}}..."
                    rows="4"
                />

            </div>

            <div class="min-h-24 w-full"></div>
            {{--                <div class="fixed bottom-0 p-4 h-32 left-0 right-0 z-20 bg-white border-t border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700 lg:hidden shadow-lg" style="padding-bottom: env(safe-area-inset-bottom, 0px); box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1), 0 -2px 4px -2px rgb(0 0 0 / 0.1);">--}}
            <div class="fixed bottom-0 p-4 h-32 left-0 right-0 z-20 bg-white  dark:bg-zinc-900  lg:hidden"  >
                <div class="flex items-center justify-between gap-4  px-4 py-4">

                    <flux:button
                        variant="outline"
                        class="w-full mobile "
                        href="{{route('home')}}"
                        wire:navigate
                    >
                        {{__('Cancel')}}
                    </flux:button>

                    {{--                    <flux:button--}}
                    {{--                        type="button"--}}
                    {{--                        class="w-full bg-zinc-400! mobile"--}}
                    {{--                        wire:click="saveDraft"--}}
                    {{--                    >{{__('Save draft')}}</flux:button>--}}

                    <flux:button type="submit"
                                 variant="primary"
                                 class="w-full mobile bg-amber-400! dark:bg-amber-700!">
                        {{__('Send Report')}}</flux:button>
                </div>
            </div>

        </form>




        <flux:modal name="image-method" class="w-full h-50 border border-neutral-content rounded-t-2xl" variant="flyout" position="bottom">
            <div class="flex flex-col gap-4 p-4 pb-0 mb-0">
                <flux:button wire:click="getImageFromCamera" class="w-full mobile" variant="primary">{{__('Take Photo')}}</flux:button>
                <flux:button wire:click="getImageFromLibrary" class="w-full mobile">{{__('Choose from Library')}}</flux:button>
            </div>
        </flux:modal>

        <!-- Load exifr FULL bundle for GPS support -->
        {{--                <script src="https://cdn.jsdelivr.net/npm/exifr@7.1.3/dist/full.umd.js"></script>--}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.addEventListener('location-picker-moved', function(event) {
                    console.log('Received location update in create blade for location picker:', event.detail.lat, event.detail.lng);
                    @this.call('handleLocationUpdate', event.detail.lat, event.detail.lng);
                });

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
                                    console.error('❌ GPS extraction error:', error);
                                });
                        } else {
                            console.log('❌ No image element or src found');
                        }
                    }, 200); // Longer delay to ensure DOM is updated
                });

            });

        </script>
    </div>



</div>


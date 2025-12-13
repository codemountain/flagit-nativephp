<div @class(["mt-4 px-4 relative mb-40",
                       "pt-4!" => \Native\Mobile\Facades\System::isAndroid(),
                       "pt-0!" => !\Native\Mobile\Facades\System::isAndroid(),
               ])>

    <x-ui.report-details-bottom-nav
        report-id="{{$report->report_id}}"  notes="{{$report->notes()->count()}}"/>

        <flux:heading size="xl">{{$report->title}}</flux:heading>
        <form wire:submit="saveImage" class="flex flex-col gap-8 mt-4">
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

            <flux:button type="submit" variant="primary" class="w-full mobile">{{__('Add Image')}}</flux:button>
        </form>

<script>
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

            // Step 2: Compress the image using Canvas API
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
        }
    });
</script>
</div>




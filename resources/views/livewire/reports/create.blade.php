<div class="space-y-6"
     x-data="imagePreviewHandler()"
     @generate-image-preview.window="generatePreview($event.detail)">
    <!-- Main Content Area with Horizontal Padding -->
    <form wire:submit="createReport" class="">


        <div class="flex justify-between gap-2 mb-8">
            <!-- Image Picker - First field -->
            <div
                wire:click="getImage"
                class="h-64 w-full flex justify-center rounded-lg border border-zinc-200 dark:border-zinc-700 p-0 items-center text-center bg-zinc-50">
                @if(!empty($photoDataUrl))
                    <img src="{{$photoDataUrl}}" class="w-full h-full object-cover relative" alt="preview" id="reportimg"/>
                @elseif($needsPreviewGeneration)
                    <!-- Show loading while preview is being generated -->
                    <div class="flex flex-col items-center justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-zinc-600 mb-4"></div>
                        <span class="text-sm text-zinc-500">Generating preview...</span>
                        <span class="text-xs text-zinc-400 mt-1">{{ $new_report['image'] }}</span>
                    </div>
                @elseif(!empty($new_report['image']))
                    <!-- Show a placeholder if preview failed -->
                    <div class="flex flex-col items-center justify-center">
                        <flux:icon.photo class="size-20 text-zinc-400 mb-2" />
                        <span class="text-sm text-zinc-500">Image captured</span>
                        <span class="text-xs text-zinc-400">{{ $new_report['image'] }}</span>
                    </div>
                @else
                    <flux:button  class="w-16 h-16 rounded-full border-0 bg-zinc-50!">
                        <flux:icon.camera class="size-20 text-zinc-400" />
                    </flux:button>
                @endif

            </div>
        </div>
        Path: {{$photoPath}}  | {{$photoDataUrl}}
        <br>
        <div>
            Files: {{$files}}
        </div>


        <div class="w-full gap-4 space-y-4 relative mt-8">
            <flux:input
                label="{{__('Title')}}"
                placeholder="{{__('Fallen tree, blocked drainage')}}..."
                wire:model="new_report.title"
                required
            />
            <flux:field variant="inline" class="w-1/3 absolute top-0 right-0">
                <flux:label>Is urgent?</flux:label>
                <flux:switch wire:model="new_report.is_urgent" />
                <flux:error name="is_urgent" />
            </flux:field>
            <flux:textarea
                label="{{__('Description')}}"
                wire:model="new_report.description"
                placeholder="{{__(' ')}}..."
                rows="2"
            />

        </div>

        <div class="min-h-24 w-full"></div>
        {{--                <div class="fixed bottom-0 p-4 h-32 left-0 right-0 z-20 bg-white border-t border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700 lg:hidden shadow-lg" style="padding-bottom: env(safe-area-inset-bottom, 0px); box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1), 0 -2px 4px -2px rgb(0 0 0 / 0.1);">--}}
        <div class="fixed bottom-0 p-4 h-32 left-0 right-0 z-20 bg-white  dark:bg-zinc-900  lg:hidden"  >
            <div class="flex items-center justify-between gap-4  px-4 py-4">

                <flux:button
                    class="w-full mobile"
                    variant="outline"
                    href="{{route('home')}}"
                    wire:navigate
                >
                    {{__('Cancel')}}
                </flux:button>


                <flux:button type="submit"
                             class="w-full bg-amber-700! mobile"
                            variant="primary"
                >
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

</div>

@script
<script>
Alpine.data('imagePreviewHandler', () => ({
    generatePreview(detail) {
        const { fullDataUrl, mimeType } = detail;

        // Create an image element
        const img = new Image();
        img.onload = function() {
            // Create canvas for resizing
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Calculate new dimensions (max 800px on longest side for preview)
            const maxSize = 800;
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > maxSize) {
                    height *= maxSize / width;
                    width = maxSize;
                }
            } else {
                if (height > maxSize) {
                    width *= maxSize / height;
                    height = maxSize;
                }
            }

            // Set canvas dimensions
            canvas.width = width;
            canvas.height = height;

            // Draw resized image
            ctx.drawImage(img, 0, 0, width, height);

            // Get preview as base64 with reduced quality
            const previewDataUrl = canvas.toDataURL(mimeType, 0.7); // 70% quality

            // Send preview back to Livewire
            @this.updatePreview(previewDataUrl);
        };

        img.onerror = function() {
            console.error('Failed to generate preview');
            // If preview generation fails, try to show a placeholder
            @this.updatePreview('');
        };

        // Load the full image
        img.src = fullDataUrl;
    }
}));
</script>
@endscript


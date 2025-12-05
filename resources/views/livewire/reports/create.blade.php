<div class="space-y-6">
    <!-- Main Content Area with Horizontal Padding -->
    <form wire:submit="createReport" class="">


        <div class="flex justify-between gap-2 mb-8">
            <!-- Image Picker - First field -->
            <div
                wire:click="getImage"
                class="h-64 w-full flex justify-center rounded-lg border border-zinc-200 dark:border-zinc-700 p-0 items-center text-center bg-zinc-50">
                @if(!empty($photoDataUrl))
                    <img src="{{$photoDataUrl}}" class="w-full h-full object-cover relative" alt="preview" id="reportimg"/>
                @else
                    <flux:button  class="w-16 h-16 rounded-full border-0 bg-zinc-50!">
                        <flux:icon.camera class="size-20 text-zinc-400" />
                    </flux:button>
                @endif

            </div>
        </div>
        Path: {{$photoPath}}  | {{$photoDataUrl}}


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


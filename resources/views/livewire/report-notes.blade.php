<div @class(["mt-4 px-4 relative mb-40",
                       "pt-4!" => \Native\Mobile\Facades\System::isAndroid(),
                       "pt-0!" => !\Native\Mobile\Facades\System::isAndroid(),
               ])>
            <native:fab
                icon="plus"
                size="regular"
                position="end"
                containerColor="#FF5C00"
                contentColor="#FFF"
                :bottomOffset="20"
                :cornerRadius="50"
                :elevation="0"
                :url="route('reports.create')"
            />
    <x-ui.report-details-bottom-nav report-id="{{$report->report_id}}" />
    @if(!empty($report->notes) && !empty($report->id))
        <div class="block w-full">
            <div class="space-y-2 mb-6">
                @foreach($report->notes as $note)
                    <x-ui.note-card :note="$note"/>
                @endforeach
            </div>


{{--            <!-- Images Modal with Carousel -->--}}
{{--            <flux:modal wire:model="showImagesModal" class="w-full h-[80%] border border-neutral-content rounded-t-2xl" variant="flyout" position="bottom">--}}
{{--                <flux:heading size="lg">{{ __('Note Images') }}</flux:heading>--}}

{{--                <div class="mt-4 relative" wire:key="carousel-{{ $currentNoteId }}" wire:model.live="currentNoteImages">--}}
{{--                    @include('components.ui.carousel-modal', ['images' => $currentNoteImages])--}}
{{--                </div>--}}

{{--                <div class="flex justify-end space-x-3 pt-4 mt-4">--}}
{{--                    <flux:button wire:click="$set('showImagesModal', false)">{{ __('Close') }}</flux:button>--}}
{{--                </div>--}}
{{--            </flux:modal>--}}
{{--            <!-- Add note modal-->--}}
{{--            <flux:modal wire:model="showAddNoteModal" class="w-full h-[80%] border border-neutral-content rounded-t-2xl" variant="flyout" position="bottom">--}}
{{--                <flux:heading size="lg">{{ __('New note') }}</flux:heading>--}}
{{--                <form wire:submit="saveNote" class="flex flex-col gap-8 mt-4">--}}
{{--                    <flux:textarea--}}
{{--                        label="{{__('Note')}}"--}}
{{--                        wire:model="new_note.content"--}}
{{--                        placeholder="{{__(' ')}}..."--}}
{{--                        rows="2"--}}
{{--                    />--}}
{{--                    <!-- Image Picker - First field -->--}}
{{--                    <div wire:show="showMethods"--}}
{{--                         class="flex flex-col justify-center items-center gap-4 p-4 w-full h-60 border border-neutral-content rounded-2xl  bg-zinc-300 dark:bg-zinc-800">--}}
{{--                        <flux:button wire:click="getImageFromCamera" class="w-full mobile" variant="primary">{{__('Take Photo')}}</flux:button>--}}
{{--                        <flux:button wire:click="getImageFromLibrary" class="w-full mobile">{{__('Choose from Library')}}</flux:button>--}}
{{--                    </div>--}}
{{--                    <div--}}
{{--                        wire:click="getImage"--}}
{{--                        wire:show="!showMethods"--}}
{{--                        class="w-full h-60 flex justify-center rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 items-center text-center bg-zinc-50">--}}
{{--                        @if(!empty($previewImage))--}}
{{--                            <img src="{{$previewImage}}" class="w-full h-full object-cover relative" alt="preview"/>--}}
{{--                        @else--}}
{{--                            <flux:button  class="w-16 h-16 rounded-full border-0 bg-zinc-50!">--}}
{{--                                <flux:icon.camera class="size-20 text-zinc-400" />--}}
{{--                            </flux:button>--}}
{{--                        @endif--}}

{{--                    </div>--}}

{{--                    <flux:button type="submit" class="w-full bg-amber-400! mobile">{{__('Add Note')}}</flux:button>--}}
{{--                </form>--}}

{{--            </flux:modal>--}}


            @else
                No notes
            @endif

        </div>

</div>


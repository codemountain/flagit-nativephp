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
                :url="route('reports.details.notes.create',['id'=>$report->report_id])"
            />

    <x-ui.report-details-bottom-nav
        report-id="{{$report->report_id}}"  notes="{{$report->notes()->count()}}"/>
    @if($report->notes()->count() > 0 && !empty($report->id))
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

            </div>
            @else
            <div class="grid auto-rows-min p-4">
                <flux:card>
                    <flux:heading size="lg">{{__('Nothing to see here!')}}</flux:heading>
                    <flux:text class="mt-2 mb-4">
                        {{__('Need to add info on this report?')}}<br>
                    </flux:text>
                    <flux:button variant="primary" icon="plus" href="{{route('reports.details.notes.create',['id'=>$report->report_id])}}" >
                        {{__('New note')}}
                    </flux:button>
                </flux:card>
            </div>
    @endif

        </div>




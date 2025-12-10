@props([
    /** @var \mixed */
    'report',
    'parent' => null,
])

<div
        {{ $attributes->class(['relative w-full mb-4 h-30 md:h-40 lg:w-full lg:h-40 xl:w-80 xl:h-30 xl:w-full  xl:mb-0 border-zinc-200 border-1 rounded-xl shadow-sm overflow-hidden hover:bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 flex']) }}>
    @if($report['is_urgent'])
    <flux:icon.marker-status
            variant="{{$report['status']}}_urgent"
            class="absolute top-0 right-0 m-2 opacity-50"
            size="6"
    />
    @else
    <flux:icon.marker-status
        variant="{{$report['status']}}"
        class="absolute top-0 right-0 m-2 opacity-50"
        size="6"
    />
    @endif


    <!-- Image on the Left -->
    <div class="w-2/5 h-full overflow-hidden relative">
        @php
            // Safely handle image/thumb fields that might be null
            $imageUrl = null;
            if (!empty($report['thumb'])) {
                $imageUrl = $report['thumb'];
            } elseif (!empty($report['image'])) {
                $imageUrl = $report['image'];
            }

//            $image = App\Helpers\MediaHelper::checkAndGet($imageUrl);
        @endphp
        <img src="{{ $imageUrl }}"
             class="w-full h-full object-cover"
             alt="">
{{--        @if($report->notes->count() > 0)--}}
{{--            <flux:icon.chat-bubble-bottom-center-text--}}
{{--                class="text-amber-600 shadow-2xl absolute top-2 left-2"/>--}}
{{--        @endif--}}
    </div>

    <!-- Content on the Right -->
    <div @class(["w-3/5 p-4 flex flex-col justify-between",
                "bg-error/10" => $report['is_urgent']
                ])>
        <h5 class="truncate text-md font-bold text-gray-900 dark:text-white"
            >
            {{ $report['title'] }}
        </h5>
{{--        @if($report['duplicate_of_id'])--}}
{{--            <flux:badge color="red">{{__('Report is marked duplicate')}}</flux:badge>--}}
{{--        @endif--}}
        <p class="text-sm text-gray-700 dark:text-gray-400 line-clamp-3">
            {{ $report['description'] }}
        </p>
        <div class="absolute bottom-2 left-2">
            <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75" >{{\Carbon\Carbon::parse($report['created_at'])->diffForHumans()}}</flux:badge>
        </div>

{{--        @if(!empty($parent))--}}
{{--            @php($distance = $report->withDistance('location', $parent->location, 'meters')->first())--}}
{{--            @if($distance)--}}
{{--                <div class="absolute top-2 left-2">--}}
{{--                    <flux:badge  size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{round($distance->meters,2)}}m</flux:badge>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--            </div>--}}
{{--        @endif--}}

    </div>
</div>

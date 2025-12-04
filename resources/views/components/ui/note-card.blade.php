@props([
    /** @var \mixed */
    'note'
])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div
        x-data="{ expanded: false }"
        {{ $attributes->class(['relative w-full border-zinc-200 border-1 rounded-xl shadow-sm overflow-hidden hover:bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 flex transition-all duration-300']) }}
        :class="expanded ? 'h-auto min-h-40' : 'h-20'">

    <!-- Image on the Left -->
    @php
        $attachmentsCount = $note->attachments->count();
        $hasMultipleImages = $attachmentsCount > 1;
        $hasImages = $note->has_images;
        ray("Note attachments: ".$attachmentsCount,$hasImages,$note->attachments);
        ray("Note:",$note);
    @endphp

    <div
        class="w-[25%] h-full overflow-hidden relative {{ $hasImages ? 'cursor-pointer' : '' }}"
        @if($hasImages)
            @click="$dispatch('open-note-images', { noteId: '{{ $note->id }}'})"
        @endif
    >
        @if($hasImages || !empty($note->note_default_image ))

            <img src="{{ $note->note_default_image }}" class="w-full h-full object-cover" />

            @if($hasMultipleImages)
                <span class="absolute pb-0.5 top-2 left-2 pt-1 bg-zinc-700 text-white text-xxs rounded-full h-3.5 w-3.5 flex items-center justify-center opacity-75"
                >{{ $attachmentsCount }}</span>
            @endif
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                <img src="{{ asset('icons/apps/flagit.svg') }}" class="w-3/4 h-3/4 object-contain opacity-30" alt="App Logo" />
            </div>
        @endif
    </div>


    <!-- Content on the Right -->
    @if(empty($note->external_id))
        <div class="absolute top-3 right-4 z-10" >
            <flux:badge color="red">Not synched</flux:badge>
        </div>
    @endif
    <div class="w-3/5 p-4 flex flex-col space-y-2">
        <div class="flex-none">
            <h5 class="text-md font-bold text-gray-900 dark:text-white">
                {{ $note->from_name ?? 'Unknown' }}
            </h5>
        </div>
        <div class="relative flex-grow">
            <p
                @click="expanded = !expanded"
                class="text-sm text-gray-700 dark:text-gray-400 cursor-pointer"
                :class="expanded ? 'mt-2 mb-4' : 'line-clamp-1'"
            >
                {{ $note->content ?? 'N/A' }}
            </p>
            <button
                x-show="expanded"
                @click="expanded = false"
                class="absolute -top-3 right-0 bg-gray-100 dark:bg-gray-600 rounded-full p-1 text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-none" x-bind:class="{ 'absolute bottom-2 left-2': !expanded }">
            <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75" >{{$note->created_at->diffForHumans()}}</flux:badge>
        </div>

    </div>
    @if($note->is_internal==true)
    <div class="absolute top-3 right-3" >

        <flux:tooltip content="Internal note" position="top">
        <flux:icon.internal variant="outline" class="!size-4 opacity-50"/>
        </flux:tooltip>
    </div>
    @endif

{{--    <div class="absolute bottom-3 right-3" x-bind:class="{ 'hidden': expanded }">--}}
{{--        <flux:icon.pencil @click="$dispatch('edit-note', { noteId: '{{ $note->id }}' })" variant="outline" class="!size-4 opacity-50 cursor-pointer hover:opacity-100"/>--}}
{{--    </div>--}}
{{--    <div class="absolute bottom-3 right-3" x-show="expanded">--}}
{{--        <flux:icon.pencil @click="expanded = false; $dispatch('edit-note', { noteId: '{{ $note->id }}' })" variant="outline" class="!size-4 opacity-50 cursor-pointer hover:opacity-100"/>--}}
{{--    </div>--}}

</div>

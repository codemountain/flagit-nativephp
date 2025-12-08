{{-- Reusable report panel component --}}
{{-- Parameters: $type, $state (from $reportStates[$type]) --}}

@if($state['isLoading'])
    <div class="grid auto-rows-min mb-4 gap-4">

        <x-ui.card-skeleton/>
        <x-ui.card-skeleton/>
        <x-ui.card-skeleton/>
        <x-ui.card-skeleton/>

    </div>
@else
    <div class="flex justify-end items-center pb-2 w-full -mt-4">
        <flux:button
            wire:click="flushReports('{{ $type }}')"
            class="absolute top-0 right-0"
            variant="outline"
            icon="arrow-path">
        </flux:button>
    </div>

    <div class="grid auto-rows-min mb-4">
        @foreach ($state['data'] as $report)
            <a class="cursor-pointer" wire:key="{{ $type }}-card-row-{{$report['report_id']}}"
               href="{{ route('reports.details', ['report' => $report['report_id']]) }}">
                <x-ui.report-card :report="$report"/>
            </a>
        @endforeach
    </div>

    @if($state['hasMore'] && !$state['isLoading'])
        <div class="mt-4 mb-4">
            <flux:button
                wire:click="loadMore('{{ $type }}')"
                class="w-full"
                icon="squares-plus"
                iconVariant="outline"
            >
                {{ $state['isLoadingMore'] ? __('Loading...') : __('More') }}
            </flux:button>
        </div>
    @endif
@endif

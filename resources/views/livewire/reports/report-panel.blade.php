{{-- Reusable report panel component --}}
{{-- Parameters: $type, $reports (SimplePaginator) --}}

<div class="grid auto-rows-min mb-4">
    @foreach ($reports as $report)
        <a class="cursor-pointer" wire:key="{{ $type }}-card-row-{{ $report->report_id }}"
           href="{{ route('reports.details', ['id' => $report->report_id]) }}">
            <x-ui.report-card :report="$report->toArray()"/>
        </a>
    @endforeach
</div>

@if($reports->hasMorePages())
    <div
        x-intersect="$wire.loadMore('{{ $type }}')"
        class="flex justify-center py-4 transition-opacity"
    >
        <flux:icon.arrow-path  class="animate-spin w-8! h-8!"></flux:icon.arrow-path>
    </div>
@else
    <div class="text-center py-4 text-gray-400">
        {{__('You\'ve reached the end of the list.')}}
    </div>
@endif

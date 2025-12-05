<div class="flex flex-col gap-4" @reports-flushed.window="$wire.loadReports()">

    <flux:tab.group>

        <flux:tabs variant="segmented" class="w-full h-14!">
            <flux:tab name="created" >{{__('Created')}}</flux:tab>
            <flux:tab name="assigned">{{__('Assigned')}}</flux:tab>
        </flux:tabs>


        <flux:tab.panel name="created" class="relative">
            @if($isLoading)
                <div class="grid auto-rows-min mb-4 gap-4">
                    @foreach(range(1, 5) as $index)
                        <flux:skeleton.group animate="shimmer" class="flex items-center gap-4">
                            <flux:skeleton class="size-14 rounded-lg" />
                            <div class="flex-1">
                                <flux:skeleton.line class="w-1/2 mb-2" />
                                <flux:skeleton.line class="mb-2" />
                                <flux:skeleton.line class="mb-2" />
                                <flux:skeleton.line class="w-3/4" />
                            </div>
                        </flux:skeleton.group>
                    @endforeach
                </div>
            @else
                <div class="flex justify-end items-center pb-2 w-full -mt-4">
                <flux:button
                    wire:click="flushReports"
                    class="absolute top-0 right-0"
                    variant="outline"
                    icon="arrow-path">
                    </flux:button>
                </div>
                <div class="grid auto-rows-min mb-4">
                    @foreach ($reports as $report)
                        <a class="cursor-pointer" wire:key="card-row-{{$report['report_id']}}"
                           href="{{ route('reports.details', ['report' => $report['report_id']]) }}">
                            <x-ui.report-card :report="$report" wire:model="reports"/>
                        </a>
                    @endforeach
                </div>

                @if($hasMorePages && !$isLoading)
                    <div class="mt-4 mb-4">
                        <flux:button
                            wire:click="loadMore"
                            variant="outline"
                            class="w-full"
                            icon="arrow-down-circle"
                        >
                            {{ $isLoadingMore ? __('Loading...') : __('Load More') }}
                        </flux:button>
                    </div>
                @endif
            @endif

            <!-- Pagination Links for Created Reports -->
{{--            <div class="mt-4 mb-40">--}}
{{--                {{ $reports->links() }}--}}
{{--            </div>--}}
        </flux:tab.panel>
        <flux:tab.panel name="assigned">
            assigned
{{--            @if($assignedReports->count() > 0)--}}
{{--                <div class="mb-4 -mt-5 text-right">--}}
{{--                    <flux:button--}}
{{--                        class="w-full mobile"--}}
{{--                        variant="outline"--}}
{{--                        href="{{route('report.map', ['type'=>'assigned'])}}"--}}
{{--                        icon="map"--}}
{{--                    >--}}
{{--                        {{__('View assigned on Map')}}--}}
{{--                    </flux:button>--}}

{{--                </div>--}}
{{--            @endif--}}
{{--            <div class="grid auto-rows-min mb-4">--}}
{{--                @foreach ($assignedReports as $report)--}}
{{--                    <a class="cursor-pointer" wire:key="card-row-{{$report->id}}"--}}
{{--                       href="{{ route('report.show', ['report' => $report->id ]) }}">--}}
{{--                        <x-ui.report-card :report="$report"/>--}}
{{--                    </a>--}}
{{--                @endforeach--}}
{{--            </div>--}}

{{--            <!-- Pagination Links for Assigned Reports -->--}}
{{--            <div class="mt-4 mb-40">--}}
{{--                {{ $assignedReports->links() }}--}}
{{--            </div>--}}
        </flux:tab.panel>
    </flux:tab.group>
</div>

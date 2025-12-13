<div
     @class(["mt-4 px-4 mb-40",
                   "pt-0!" => \Native\Mobile\Facades\System::isAndroid(),
                   "pt-0!" => !\Native\Mobile\Facades\System::isAndroid(),
           ])>
    <x-ui.report-details-bottom-nav report-id="{{$report->report_id}}" notes="{{$report->notes()->count()}}"/>
    @if(!empty($report) && !empty($report->id))
        <div class="block w-full">
            <!-- First section - carousel (full width on mobile, half width on desktop) -->
            <div class="w-full mb-6">
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-xl flex justify-between items-center gap-2 w-full">
                        <div>{{!empty($report->trail_name) ? $report->trail_name :  __('Trail TBD')}}</div>

                        <flux:icon.marker-status variant="{{$report->status ?? 'submitted'}}" class="w-8! h-8!"/>
                    </div>

                </div>
                <div class="text-lg text-zinc-500 dark:text-zinc-400">{{$report->title ?? __('Title missing')}}</div>
                <!-- Carousel container -->
                <div class="flex items-center gap-2 justify-end">
                    <div class="relative bg-primary w-full p-0 rounded-lg mt-4">

                        <img
                            src="{{$report->image}}"
                            class="w-full h-full object-cover relative rounded-t-2xl"
                        />
                        @if(!empty($report->images) && count($report->images) > 1)
                        <div class="absolute top-2 right-2">
                            <flux:button
                                href="{{route('reports.details.images',['id'=>$report->report_id])}}"
                                class="w-16 h-16 opacity-70"
                                variant="primary"
                            >
                                <flux:icon.photo class="size-8 text-zinc-300 dark:text-zinc-300" variant="solid" />
                            </flux:button>
                        </div>
                        @endif
                        <div class="absolute bottom-2 right-2">
                            <flux:button
                                href="{{route('reports.details.map',['id'=>$report->report_id])}}"
                                class="w-16 h-16 opacity-70"
                                variant="primary"
                            >
                                <flux:icon.map class="size-8 text-zinc-300 dark:text-zinc-300" variant="solid" />
                            </flux:button>

                        </div>
                    </div>
                </div>

                <div class="p-6 bg-zinc-400 dark:bg-zinc-700 mt-0 rounded-b-2xl relative">
                    <div class="text-sm text-zinc-50 dark:text-zinc-200">
                        {{$report->description ?? __('No description given')}}
                    </div>
                </div>

                <div class="py-6">
                    @if($report->category_names)
                        <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4">
                            <flux:heading size="sm text-bold">{{__('Categories')}}</flux:heading>
                            @foreach(explode(',', $report->category_names) as $item)
                                <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>
                            @endforeach
                        </div>
                    @endif
                    @if($report->skill_names)
                        <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">
                        <flux:heading size="sm text-bold">{{__('Skills')}}</flux:heading>
                        @foreach(explode(',', $report->skill_names) as $item)
                            <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>
                        @endforeach
                </div>
                @endif
                @if($report->material_names)
                    <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">
                    <flux:heading size="sm text-bold">{{__('Materials')}}</flux:heading>
                    @foreach(explode(',', $report->material_names) as $item)
                        <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>
                    @endforeach
            </div>
            @endif
            @if($report->equipment_names)
                <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">
                <flux:heading size="sm text-bold">{{__('Materials')}}</flux:heading>
                @foreach(explode(',', $report->equipment_names) as $item)
                    <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>
                @endforeach
        </div>
    @endif
    @if($report->task_names)
        <div class="flex flex-wrap gap-2 items-center p-2 bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4"">
        <flux:heading size="sm text-bold">{{__('Tasks')}}</flux:heading>
        @foreach(explode(',', $report->task_names) as $item)
            <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75">{{Str::ucfirst($item)}}</flux:badge>
        @endforeach
</div>
@endif

<div class="w-full text-sm text-right text-zinc-500 dark:text-zinc-400 mt-4 pr-2 italic">
    <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75 mb-2" >{{\Carbon\Carbon::parse($report->created_at)->diffForHumans()}}</flux:badge>
    <flux:badge size="2" class="!text-xs opacity-75 mb-2 !bg-status-{{$report->status}}" >{{__('Status')}}: {{$report->status ?? 'unknown'}}</flux:badge>
    <br>
    {{__('Submitted on')}}: {{\Carbon\Carbon::parse($report->created_at)->format('d-m-Y @ H:i')}}<br> {{$report->created_by_name ?? __('Unknown')}}

    <br>
{{--    @if(!empty($report->createdByUser))--}}
{{--        {{$report->createdByUser->fullname}} - {{$report->createdByUser->email}}--}}
{{--    @endif--}}
</div>


</div>
<flux:modal name="report-worklogs" class="w-full h-[87%] border border-neutral-content rounded-t-2xl p-0!" variant="flyout" position="bottom" closable="false">
{{--    @if($worklogs)--}}
{{--        <div class="w-full mb-6 mt-4 p-6">--}}
{{--            <flux:heading size="xl" level="1">{{__('Worklogs')}}</flux:heading>--}}
{{--            @if(!empty($worklogs))--}}
{{--                <div class="mt-4 text-sm grid grid-cols-2 gap-2 bg-lime-700/20  rounded-xl p-4">--}}
{{--                    @php $totalDuration = 0; @endphp--}}
{{--                    @foreach($worklogs as $log)--}}
{{--                        <div>{{$log['performer_name']}}--}}
{{--                            <br>--}}
{{--                            <flux:tooltip content="{{$log['description']}}">--}}
{{--                                {{Str::limit($log['description'],20,'...')}}--}}
{{--                            </flux:tooltip>--}}
{{--                        </div>--}}

{{--                        <div class="flex items-center justify-end">--}}
{{--                            @if($log['duration'] < 60)--}}
{{--                                {{$log['duration']}} {{__('min.')}}--}}
{{--                            @else--}}
{{--                                {{round($log['duration']/60,1)}} {{__('hours.')}}--}}
{{--                            @endif--}}
{{--                            <br>--}}
{{--                            {{$log['performed_at']}}--}}
{{--                        </div>--}}
{{--                        @php $totalDuration += $log['duration']; @endphp--}}
{{--                    @endforeach--}}

{{--                </div>--}}
{{--                <div class="mt-4 text-sm w-full bg-lime-700/20  rounded-xl p-4">--}}
{{--                    <div class="flex justify-end text-right">{{__('Total Duration')}}: {{$totalDuration}} {{__('min.')}} | {{ round($totalDuration/60,1) }} {{__('hours.')}}</div>--}}
{{--                </div>--}}
{{--            @else--}}
{{--                <div class="mt-4 text-sm grid grid-cols-1 gap-2 bg-zinc-700/20  rounded-xl p-4">--}}
{{--                    {{__('No worklogs found.')}}--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endif--}}
</flux:modal>
<flux:modal name="report-fixit" class="w-full h-[87%] border border-neutral-content rounded-t-2xl p-0!" variant="flyout" position="bottom" closable="false">
{{--    @if($report)--}}
{{--        <livewire:reports.fixit :report="$report" />--}}
{{--    @endif--}}
    Fix it here
</flux:modal>

@else
    No report valid
    @endif


    </div>

    </div>

</div>

<div class="grid auto-rows-min mt-4 px-4 py-8 gap-4 mb-40">

    @if($userReportsCount == 0)
        <div class="grid auto-rows-min p-4">
            <flux:card>
                <flux:heading size="lg">{{__('Welcome!')}}</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{__('Submit your first FlagIt report now!')}}<br>
                </flux:text>
                <flux:button variant="primary" icon="plus" href="{{route('reports.create')}}">
                    {{__('Create report')}}
                </flux:button>
            </flux:card>
        </div>
    @endif
    <!-- Created Reports Section -->
    <flux:card @class(["bg-amber-700 dark:bg-amber-700/30  border-0 pb-8 pt-[var(--inset-top)]",
                        "pt-4!" => \Native\Mobile\Facades\System::isAndroid(),
                        "pt-2!" => !\Native\Mobile\Facades\System::isAndroid(),
                ])>
        <div @class(["flex items-center justify-between mb-4",
                    "pt-4" => \Native\Mobile\Facades\System::isAndroid(),
                    "pt-0!" => !\Native\Mobile\Facades\System::isAndroid(),
            ])>
            <flux:heading size="lg">{{ __('My Reports') }}</flux:heading>
            <flux:button
                wire:click="startCreatedSync"
                :disabled="$createdSyncing"
                variant="outline"
                icon="arrow-path"
                class="opacity-50!"
            >
                {{ $createdSyncing ? __('Syncing all...') : __('Sync All') }}
            </flux:button>
        </div>

        @if($createdComplete)
            <flux:badge color="green">{{ __('Complete') }} - {{ $createdTotal }} {{ __('reports') }}</flux:badge>
        @elseif($createdSyncing || $createdTotal > 0)
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span>{{ __('Progress') }}</span>
                    <span>{{ $createdProgress }} / {{ $createdTotal }}</span>
                </div>
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                    <div
                        class="bg-green-500 h-2 rounded-full transition-all duration-300"
                        style="width: {{ $createdTotal > 0 ? min(($createdProgress / $createdTotal) * 100, 100) : 0 }}%"
                    ></div>
                </div>
            </div>
        @endif
    </flux:card>


    <!-- Assigned Reports Section -->
    <flux:card class="bg-amber-700 dark:bg-amber-700/30  border-0 pb-8 pt-[var(--inset-top)]">
        <div class="flex items-center justify-between mb-4 pt-4">
            <flux:heading size="lg">{{ __('Assigned Reports') }}</flux:heading>
            <flux:button
                wire:click="startAssignedSync"
                :disabled="$assignedSyncing"
                variant="outline"
                icon="arrow-path"
                class="opacity-50!"
            >
                {{ $assignedSyncing ? __('Syncing...') : __('Sync All') }}
            </flux:button>
        </div>

        @if($assignedComplete)
            <flux:badge color="green">{{ __('Complete') }} - {{ $assignedTotal }} {{ __('reports') }}</flux:badge>
        @elseif($assignedSyncing || $assignedTotal > 0)
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span>{{ __('Progress') }}</span>
                    <span>{{ $assignedProgress }} / {{ $assignedTotal }}</span>
                </div>
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                    <div
                        class="bg-green-500 h-2 rounded-full transition-all duration-300"
                        style="width: {{ $assignedTotal > 0 ? min(($assignedProgress / $assignedTotal) * 100, 100) : 0 }}%"
                    ></div>
                </div>
            </div>
        @endif
    </flux:card>

    <!-- Back button when both complete -->
{{--    @if($createdComplete && $assignedComplete)--}}
{{--        <flux:button href="{{ route('home') }}" variant="filled" icon="home">--}}
{{--            {{ __('Back to Home') }}--}}
{{--        </flux:button>--}}
{{--    @endif--}}
</div>

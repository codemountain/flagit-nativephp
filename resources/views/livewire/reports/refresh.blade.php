<div class="grid auto-rows-min mb-4 gap-4">
    <flux:heading size="xl">{{ __('Sync Reports') }}</flux:heading>

    <!-- Created Reports Section -->
    <flux:card class="bg-gradient-to-br rounded-2xl from-orange-600 to-amber-600 dark:from-orange-900/30 dark:to-amber-900/30 border:0 pb-8 pt-[var(--inset-top)]">
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">{{ __('My Reports') }}</flux:heading>
            <flux:button
                wire:click="startCreatedSync"
                :disabled="$createdSyncing"
                variant="outline"
                icon="arrow-path"
            >
                {{ $createdSyncing ? __('Syncing...') : __('Sync') }}
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
    <flux:card class="bg-gradient-to-br rounded-2xl from-orange-600 to-amber-600 dark:from-orange-900/30 dark:to-amber-900/30 border:0 pb-8 pt-[var(--inset-top)]">
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">{{ __('Assigned Reports') }}</flux:heading>
            <flux:button
                wire:click="startAssignedSync"
                :disabled="$assignedSyncing"
                variant="outline"
                icon="arrow-path"
            >
                {{ $assignedSyncing ? __('Syncing...') : __('Sync') }}
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

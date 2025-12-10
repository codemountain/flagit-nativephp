<div class="grid auto-rows-min mb-4 gap-4">
    <flux:heading size="xl">{{ __('Sync Reports') }}</flux:heading>

    <!-- Created Reports Section -->
    <flux:card>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">{{ __('My Reports') }}</flux:heading>
            <flux:button
                wire:click="startCreatedSync"
                :disabled="$createdSyncing"
                variant="primary"
                icon="arrow-path"
            >
                {{ $createdSyncing ? __('Syncing...') : __('Sync') }}
            </flux:button>
        </div>

        @if($createdComplete)
            <flux:badge color="green" class="mb-2">{{ __('Complete') }} - {{ $createdTotal }} {{ __('reports') }}</flux:badge>
        @elseif($createdSyncing)
            <flux:badge color="blue" class="mb-2">{{ __('Syncing page') }} {{ $createdPage }}...</flux:badge>
        @endif

        @if(count($createdLog) > 0)
            <div class="text-sm text-zinc-500 dark:text-zinc-400 space-y-1 max-h-32 overflow-y-auto">
                @foreach($createdLog as $log)
                    <div>{{ $log }}</div>
                @endforeach
            </div>
        @endif
    </flux:card>

    <!-- Assigned Reports Section -->
    <flux:card>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">{{ __('Assigned Reports') }}</flux:heading>
            <flux:button
                wire:click="startAssignedSync"
                :disabled="$assignedSyncing"
                variant="primary"
                icon="arrow-path"
            >
                {{ $assignedSyncing ? __('Syncing...') : __('Sync') }}
            </flux:button>
        </div>

        @if($assignedComplete)
            <flux:badge color="green" class="mb-2">{{ __('Complete') }} - {{ $assignedTotal }} {{ __('reports') }}</flux:badge>
        @elseif($assignedSyncing)
            <flux:badge color="blue" class="mb-2">{{ __('Syncing page') }} {{ $assignedPage }}...</flux:badge>
        @endif

        @if(count($assignedLog) > 0)
            <div class="text-sm text-zinc-500 dark:text-zinc-400 space-y-1 max-h-32 overflow-y-auto">
                @foreach($assignedLog as $log)
                    <div>{{ $log }}</div>
                @endforeach
            </div>
        @endif
    </flux:card>

    <!-- Back button when both complete -->
    @if($createdComplete && $assignedComplete)
        <flux:button href="{{ route('home') }}" variant="filled" icon="home">
            {{ __('Back to Home') }}
        </flux:button>
    @endif
</div>

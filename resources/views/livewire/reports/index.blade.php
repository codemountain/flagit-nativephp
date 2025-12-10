<div class="flex flex-col">
    <flux:tab.group class="mb-0!">
        <flux:tabs variant="segmented" class="w-full h-14! mb-0! rounded-t! rounded-b-none!">
            <flux:tab name="created">
                {{ __('Created') }}
                <flux:badge size="sm">{{ $createdReports->count() }}</flux:badge>
            </flux:tab>
            <flux:tab name="assigned">
                {{ __('Assigned') }}
                <flux:badge size="sm">{{ $assignedReports->count() }}</flux:badge>
            </flux:tab>
        </flux:tabs>

        <flux:tab.panel name="created" class="relative pt-0!">
            @include('livewire.reports.report-panel', [
                'type' => 'created',
                'reports' => $createdReports,
                'lastSyncedAt' => $createdLastSyncedAt
            ])
        </flux:tab.panel>
        <flux:tab.panel name="assigned" class="relative  pt-0!">
            @include('livewire.reports.report-panel', [
                'type' => 'assigned',
                'reports' => $assignedReports,
                'lastSyncedAt' => $assignedLastSyncedAt
            ])
        </flux:tab.panel>
    </flux:tab.group>
</div>

<div class="flex flex-col gap-4">
    <flux:tab.group>

        <flux:tabs variant="segmented" class="w-full h-14!">
            <flux:tab name="created">
                {{ __('Created') }}
                <flux:badge size="sm">{{ $createdReports->count() }}</flux:badge>
            </flux:tab>
            <flux:tab name="assigned">
                {{ __('Assigned') }}
{{--                <flux:badge size="sm">{{ $assignedReports->count() }}</flux:badge>--}}
            </flux:tab>
        </flux:tabs>

        <flux:tab.panel name="created" class="relative">
            @include('livewire.reports.report-panel', [
                'type' => 'created',
                'reports' => $createdReports
            ])
        </flux:tab.panel>
        <flux:tab.panel name="assigned" class="relative">
            @include('livewire.reports.report-panel', [
                'type' => 'assigned',
                'reports' => $assignedReports
            ])
        </flux:tab.panel>
    </flux:tab.group>
</div>

<div class="flex flex-col gap-4"
     @reports-flushed-created.window="$wire.loadReports('created')"
     @reports-flushed-assigned.window="$wire.loadReports('assigned')">

    <flux:tab.group>

        <flux:tabs variant="segmented" class="w-full h-14!">
            <flux:tab name="created" >{{__('Created')}}</flux:tab>
            <flux:tab name="assigned">{{__('Assigned')}}</flux:tab>
        </flux:tabs>


        <flux:tab.panel name="created" class="relative">
            @include('livewire.reports.report-panel', [
                'type' => 'created',
                'state' => $reportStates['created']
            ])
        </flux:tab.panel>
        <flux:tab.panel name="assigned" class="relative">
            @include('livewire.reports.report-panel', [
                'type' => 'assigned',
                'state' => $reportStates['assigned']
            ])
        </flux:tab.panel>
    </flux:tab.group>
</div>

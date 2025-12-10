<div class="flex flex-col gap-4">
    @if(!empty($reportStates))
    <flux:tab.group>

        <flux:tabs variant="segmented" class="w-full h-14!">
            <flux:tab name="created" >
                {{__('Created')}} <flux:badge color="zinc" size="sm">{{$myReportsCount}}</flux:badge>
            </flux:tab>
            <flux:tab name="assigned">{{__('Assigned')}}<flux:badge color="zinc" size="sm">{{$myAssignedCount}}</flux:badge></flux:tab>

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
    @endif
</div>

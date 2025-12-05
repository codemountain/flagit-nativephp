<div>
@if(!$connected)
    <flux:callout
        variant="warning"
        icon="chart-bar"
        heading="{{__('You appear to be offline. Some features may not work')}}"
        class="mb-4">
        <x-slot name="controls">
            <flux:button icon="arrow-path" variant="ghost" wire:click="getNetwork" />
        </x-slot>
    </flux:callout>
@endif
</div>

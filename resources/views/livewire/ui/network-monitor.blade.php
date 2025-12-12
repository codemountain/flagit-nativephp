<div>
@if(!$connected)
    <flux:callout
        variant="warning"
        icon="chart-bar"
        :heading="$statusMessage"
        class="mb-4 border-0! rounded-t-none">
        <x-slot name="controls">
            <flux:button icon="arrow-path" variant="ghost" wire:click="getNetwork" />
        </x-slot>
    </flux:callout>
{{--@else--}}
{{--    <flux:callout--}}
{{--        variant="success"--}}
{{--        icon="chart-bar"--}}
{{--        :heading="$statusMessage"--}}
{{--        class="mb-4 border-0! rounded-t-none">--}}
{{--        <x-slot name="controls">--}}
{{--            <flux:button icon="arrow-path" variant="ghost" wire:click="getNetwork" />--}}
{{--        </x-slot>--}}
{{--    </flux:callout>--}}
@endif
</div>

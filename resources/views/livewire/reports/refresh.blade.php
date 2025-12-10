
    <div class="grid auto-rows-min mb-4 gap-4">
        <flux:heading size="xl">{{__('Synching data')}}</flux:heading>
        <flux:subheading><div wire:model.live="reportCount">{{__('Reports')}}: {{$reportCount}}</div></flux:subheading>
        <x-ui.card-skeleton/>
        <x-ui.card-skeleton/>
        <x-ui.card-skeleton/>


    </div>


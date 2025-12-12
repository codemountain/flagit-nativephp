<div class="flex flex-col gap-6 mb-40">
        <flux:callout color="green" class="mb-0 animate-pulse" inline>
            <div class="flex justify-start items-center gap-2">
            <flux:icon name="{{$icon}}" class="w-8! h-8! text-teal-400" />
            <flux:callout.heading class="text-xl!">
                {{__($message)}}
            </flux:callout.heading>
            </div>
            <x-slot name="actions">

                <form method="POST" action="/logout" class="w-full">
                    @csrf
                    <flux:button class="w-full mr-6"
                                 type="submit"
                                 variant="outline"
                                 icon="home"
                    >
                        {{__('Log back in instead')}}
                    </flux:button>
                </form>
            </x-slot>
        </flux:callout>
</div>

<div class="space-y-6">
    <!-- Header with Gradient -->
    <div class="bg-accent/80 dark:bg-accent/60 border:0 pb-8 pt-[var(--inset-top)] px-6">
        <div class="space-y-3">
            <div class="flex items-start gap-4 p-2">
                <div class="space-y-3">
                    <h1 class="text-white text-3xl font-bold flex items-center space-x-6 pt-2">
                        {{__('Geolocation')}}
                    </h1>
                    <p class="text-lg text-white">
                        {{__('Flag!t needs to be able to get your location to work')}}
                    </p>
                </div>
            </div>
        </div>
        @if($result)
                <flux:heading icon="map" class="text-orange-900 dark:text-orange-100 mb-4 mt-4">Result:</flux:heading>
                <div class="p-4 bg-white/50 dark:bg-gray-800/50 rounded-lg border-2 border-white/50 backdrop-blur-sm">
                    <p class="text-sm whitespace-pre-wrap font-mono font-semibold">{{ $result }}</p>
                </div>

        @endif
    </div>
    <!-- Main Content Area with Horizontal Padding -->
    <div class="space-y-4 px-4">

        <flux:card wire:show="isChecking == true"  class="bg-zinc-50 dark:bg-zinc-800/50">
            <div class="flex justify-between items-center p-4">
                <flux:icon.arrow-path class="animate-spin"></flux:icon.arrow-path>
                <flux:heading size="lg">{{__('Checking if geolocation allowed...')}}</flux:heading>
            </div>
        </flux:card>

        <flux:card wire:show="showRetry == true" class="bg-zinc-50 dark:bg-zinc-800/50">
            <flux:button
                wire:click="requestPermission"
                icon="lock-open"
                class="py-6 w-full bg-gradient-to-br from-emerald-500 to-teal-500 !text-white border-0 shadow-lg transition-all text-xl font-semibold [&>span]:!text-white"
            >
                {{__('Request Permission')}}
            </flux:button>
        </flux:card>

        @if(\Native\Mobile\Facades\SecureStorage::get('current_latitude') && \Native\Mobile\Facades\SecureStorage::get('current_longitude'))
            <!-- Go seomwhere card -->
            <flux:card class="bg-zinc-50 dark:bg-zinc-800/50">
                <flux:button
                    wire:navigate
                    href="{{route('home')}}"
                    icon="arrow-right-end-on-rectangle"
                    class="py-6 w-full bg-gradient-to-br from-amber-700 to-orange-700 !text-white border-0 shadow-lg transition-all text-xl font-semibold [&>span]:!text-white"
                >
                    {{__('Continue')}}
                </flux:button>
            </flux:card>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //call wire method checkPermissions
            setTimeout(() => {
                console.log('check if permissions are there...');
                @this.call('checkPermissions');
            },200);
        });
    </script>
</div>

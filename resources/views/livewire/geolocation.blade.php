<div class="space-y-6">
    <!-- Header with Gradient -->
    <div class="bg-gradient-to-br rounded-2xl from-orange-600 to-amber-600 dark:from-orange-900/30 dark:to-amber-900/30 border:0 pb-8 pt-[var(--inset-top)] px-6">
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
{{--    @if($result)--}}
{{--        <flux:card class="bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 border-2 border-orange-200 dark:border-orange-700">--}}
{{--            <flux:heading icon="map" class="text-orange-900 dark:text-orange-100 mb-4">Result:</flux:heading>--}}
{{--            <div class="p-4 bg-white/50 dark:bg-gray-800/50 rounded-lg border-2 border-white/50 backdrop-blur-sm">--}}
{{--                <p class="text-sm whitespace-pre-wrap font-mono font-semibold">{{ $result }}</p>--}}
{{--            </div>--}}
{{--        </flux:card>--}}
{{--    @endif--}}
    @if(\Native\Mobile\Facades\SecureStorage::get('current_latitude') && \Native\Mobile\Facades\SecureStorage::get('current_longitude'))
        <!-- Go seomwhere card -->
        <flux:card class="bg-zinc-50 dark:bg-zinc-800/50">
            <flux:button
                href="{{route('reports.create')}}"
                icon="plus"
                class="py-6 w-full bg-gradient-to-br from-amber-700 to-orange-700 !text-white border-0 shadow-lg transition-all text-xl font-semibold [&>span]:!text-white"
            >
                {{__('Create new report')}}
            </flux:button>
        </flux:card>
    @endif
    <!-- Main Content Area with Horizontal Padding -->
    <div class="space-y-4 px-4">
        @if($isChecking)
        <flux:card class="bg-zinc-50 dark:bg-zinc-800/50">
            <div class="flex justify-between items-center p-4">
                <flux:icon.arrow-path class="animate-spin"></flux:icon.arrow-path>
                <flux:heading size="lg">{{__('Checking if geolocation allowed...')}}</flux:heading>
            </div>
        </flux:card>
        @endif
{{--        <!-- Request Permission Card -->--}}
{{--        <flux:card class="bg-zinc-50 dark:bg-zinc-800/50">--}}
{{--            <flux:button--}}
{{--                href="{{route('reports.create')}}"--}}
{{--                icon="lock-open"--}}
{{--                class="py-6 w-full bg-gradient-to-br from-amber-500 to-orange-500 !text-white border-0 shadow-lg transition-all text-xl font-semibold [&>span]:!text-white"--}}
{{--            >--}}
{{--                Request Permission--}}
{{--            </flux:button>--}}
{{--        </flux:card>--}}

{{--        <!-- Get Location Card -->--}}
{{--        <flux:card class="bg-zinc-50 dark:bg-zinc-800/50">--}}
{{--            <flux:button--}}
{{--                wire:click="getLocation"--}}
{{--                icon="map-pin"--}}
{{--                class="py-6 w-full bg-gradient-to-br from-orange-500 to-amber-500 !text-white border-0 shadow-lg transition-all text-xl font-semibold [&>span]:!text-white"--}}
{{--            >--}}
{{--                Get Location--}}
{{--            </flux:button>--}}
{{--        </flux:card>--}}


        <div class="pb-32"></div>
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

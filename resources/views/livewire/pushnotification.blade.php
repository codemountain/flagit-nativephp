<div class="space-y-6">
    <!-- Header with Gradient -->
    <div class="bg-gradient-to-br rounded-2xl from-orange-600 to-amber-600 dark:from-orange-900/30 dark:to-amber-900/30 border:0 pb-8 pt-[var(--inset-top)] px-6">
        <div class="space-y-3">
            <div class="flex items-start gap-4 p-2">
                <div class="space-y-3">
                    <h1 class="text-white text-3xl font-bold flex items-center space-x-6 pt-2">
                        {{__('Push Notification')}}
                    </h1>
                    <p class="text-lg text-white">
                        {{__('Flag!t needs your permission to send you notifications')}}
                    </p>
                </div>
            </div>
            <!-- Request Notifications Card -->

                <flux:button
                    wire:click="promptForPushNotifications"
                    icon="bell"
                    class="py-6 w-full bg-gradient-to-br from-orange-300 to-orange-300 !text-zinc-200 border-0 shadow-lg transition-all text-xl [&>span]:!text-zinc-200"
                >
                    {{__('Connect to Notifications')}}
                </flux:button>

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
    @if($hasPermission)
        <!-- Go somewhere card -->
        <flux:card class="bg-zinc-50 dark:bg-zinc-800/50">
            <flux:button
                href="{{route('home')}}"
                icon="arrow-right-end-on-rectangle"
                class="py-6 w-full bg-gradient-to-br from-amber-700 to-orange-700 !text-white border-0 shadow-lg transition-all text-xl font-semibold [&>span]:!text-white"
            >
                {{__('Continue')}}
            </flux:button>
        </flux:card>
    @endif


</div>

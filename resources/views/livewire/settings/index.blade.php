<div class="mb-40">
    <div @class(["max-w-4xl mx-auto",
                       "pt-0!" => \Native\Mobile\Facades\System::isAndroid(),
                       "pt-0! px-4 " => !\Native\Mobile\Facades\System::isAndroid(),
               ])>
        {{-- Appearance Section --}}
        <div @class(["mt-4",
                   "pt-0!" => \Native\Mobile\Facades\System::isAndroid(),
                   "pt-0!" => !\Native\Mobile\Facades\System::isAndroid(),
           ])>
            <h2 class="text-zinc-500 dark:text-zinc-400 text-xs uppercase font-semibold px-4 mb-2 tracking-wider">{{ __('Appearance') }}</h2>
            <div class="bg-white dark:bg-zinc-900 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700 p-4">
{{--                <div class="flex items-center gap-3 mb-3">--}}
{{--                    <div class="w-7 h-7 rounded-md flex items-center justify-center bg-purple-600">--}}
{{--                        <flux:icon name="swatch" class="text-white size-4" />--}}
{{--                    </div>--}}
{{--                    <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Theme') }}</p>--}}
{{--                </div>--}}
                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                    <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                    <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                    <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
                </flux:radio.group>
            </div>
        </div>

        {{-- Account Section --}}
        <div class="mt-8">
            <h2 class="text-zinc-500 dark:text-zinc-400 text-xs uppercase font-semibold px-4 mb-2 tracking-wider">{{ __('Account') }}</h2>
            <div class="bg-white dark:bg-zinc-900 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                <a
                    href="{{ route('profile') }}"
                    wire:navigate
                    class="flex items-center p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                >
                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-gradient-to-br from-blue-500 to-cyan-500 shadow-md">
                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($userName, 0, 1)) }}</span>
                    </div>
                    <div class="flex-grow">
                        <p class="font-semibold leading-tight text-zinc-900 dark:text-zinc-100">{{ $userName }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $userEmail }}</p>
                    </div>
                    <flux:icon name="chevron-right" class="text-zinc-400 dark:text-zinc-600 size-5" />
                </a>
            </div>
        </div>

        {{-- Two-column grid for settings sections --}}
        <div class="grid grid-cols-1 gap-x-6">
            <div>
                {{-- Podcast Defaults --}}
                <div class="mt-8">
                    <h2 class="text-zinc-500 dark:text-zinc-400 text-xs uppercase font-semibold px-4 mb-2 tracking-wider">{{ __('General') }}</h2>
                    <div class="bg-white dark:bg-zinc-900 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                        {{-- Podcast Defaults (Voice, Episode, Custom Instructions) --}}
                        <a
                            wire:click="viewAppSettings"
                            wire:navigate
                            class="flex items-center p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                        >
                            <div class="w-7 h-7 rounded-md flex items-center justify-center mr-4 bg-blue-600">
                                <flux:icon name="device-phone-mobile" class="text-white size-4" />
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('View Mobile App Settings') }}</p>
                            </div>
                            <flux:icon name="chevron-right" class="text-zinc-400 dark:text-zinc-600 size-5" />
                        </a>
                    </div>
                    <div class="bg-white dark:bg-zinc-900 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                        {{-- Podcast Defaults (Voice, Episode, Custom Instructions) --}}
                        <a
                            href="/"
                            wire:navigate
                            class="flex items-center p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                        >
                            <div class="w-7 h-7 rounded-md flex items-center justify-center mr-4 bg-amber-500">
                                <flux:icon name="speaker-wave" class="text-white size-4" />
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Voice samples') }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Samples of voices in multiple languages') }}</p>
                            </div>
                            <flux:icon name="chevron-right" class="text-zinc-400 dark:text-zinc-600 size-5" />
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Logout Section --}}
        <div class="mt-8 mb-8">
            <div class="bg-white dark:bg-zinc-900 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                <form method="POST" action="/logout" class="w-full">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center p-3 w-full hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer text-left"
                    >
                        <div class="w-7 h-7 rounded-md flex items-center justify-center mr-4 bg-red-600">
                            <flux:icon name="arrow-right-start-on-rectangle" class="text-white size-4" />
                        </div>
                        <p class="flex-grow font-medium text-zinc-900 dark:text-zinc-100">{{ __('Log Out') }}</p>
                        <flux:icon name="arrow-right-start-on-rectangle" class="text-zinc-400 dark:text-zinc-600 size-5" />
                    </button>
                </form>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="mt-8 mb-8">
            <h2 class="text-zinc-500 dark:text-zinc-400 text-xs uppercase font-semibold px-4 mb-2 tracking-wider">{{ __('Permissions') }}</h2>
            <div class="bg-white dark:bg-zinc-900 rounded-t-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                {{-- Podcast Defaults (Voice, Episode, Custom Instructions) --}}
                <a
                    wire:click="viewAppSettings"
                    wire:navigate
                    class="flex items-center p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                >
                    <div class="w-7 h-7 rounded-md flex items-center justify-center mr-4 bg-blue-600">
                        <flux:icon name="device-phone-mobile" class="text-white size-4" />
                    </div>
                    <div class="flex-grow">
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{Str::title(\Native\Mobile\Facades\SecureStorage::get('device_os') ?? 'phone')}} {{ __('Settings') }}</p>
                    </div>
                    <flux:icon name="chevron-right" class="text-zinc-400 dark:text-zinc-600 size-5" />
                </a>
            </div>
            <div class="bg-white dark:bg-zinc-900 overflow-hidden border border-zinc-200 dark:border-zinc-700">
                    <button
                        wire:click="resetPermissions"
                        class="flex items-center p-3 w-full hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer text-left"
                    >
                        <div class="w-7 h-7 rounded-md flex items-center justify-center mr-4 bg-green-600">
                            <flux:icon name="map-pin" class="text-white size-4" />
                        </div>
                        <p class="flex-grow font-medium text-zinc-900 dark:text-zinc-100">{{ __('Reset Geo permissions') }}</p>
                        <flux:icon name="arrow-path-rounded-square" class="text-zinc-400 dark:text-zinc-600 size-5" />
                    </button>
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-b-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                <button
                    wire:click="resetPushPermissions"
                    class="flex items-center p-3 w-full hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer text-left"
                >
                    <div class="w-7 h-7 rounded-md flex items-center justify-center mr-4 bg-teal-600">
                        <flux:icon name="bell-alert" class="text-white size-4" />
                    </div>
                    <p class="flex-grow font-medium text-zinc-900 dark:text-zinc-100">{{ __('Reset Notification permissions') }}</p>
                    <flux:icon name="arrow-path-rounded-square" class="text-zinc-400 dark:text-zinc-600 size-5" />
                </button>
            </div>
{{--            <div class="bg-white dark:bg-zinc-900 rounded-b-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">--}}
{{--                <button--}}
{{--                    wire:click="resetPermissions"--}}
{{--                    class="flex items-center p-3 w-full hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer text-left"--}}
{{--                >--}}
{{--                    <div class="w-7 h-7 rounded-md flex items-center justify-center mr-4 bg-red-600">--}}
{{--                        <flux:icon name="arrow-path" class="text-white size-4" />--}}
{{--                    </div>--}}
{{--                    <div class="flex-grow">--}}
{{--                        <p class="flex-grow font-medium text-zinc-900 dark:text-zinc-100">{{ __('Something else') }}</p>--}}
{{--                        <p class="text-sm text-zinc-500 dark:text-zinc-400">subtext</p>--}}
{{--                    </div>--}}
{{--                    <flux:icon name="chevron-right" class="text-zinc-400 dark:text-zinc-600 size-5" />--}}
{{--                </button>--}}
{{--            </div>--}}
        </div>
</div>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')

</head>
<body class="bg-white dark:bg-zinc-950 min-h-screen animate-[slideInFromRight_0.3s_ease-out] nativephp-safe-area">

    @if(!blank(\Native\Mobile\Facades\SecureStorage::get('api_token')))
        <native:top-bar title="{{ $title ?? config('app.name') }}" :show-navigation-icon="true">
            <native:top-bar-action id="profile-action" label="Home" icon="user" url="{{ route('profile') }}"/>
        </native:top-bar>

        <native:side-nav gestures_enabled="{{(\Native\Mobile\Facades\System::isIos()) ? request()->routeIs('home'):null}}">
            <native:side-nav-header
                title="Welcome"
                subtitle="{{\Native\Mobile\Facades\SecureStorage::get('user_name')}}"
                icon="info"
                :show-close-button="true"
                :pinned="true"
            />
            <native:side-nav-item id="nav-home" label="Home" icon="home" url="{{ route('home') }}" active="{{ request()->routeIs('home') }}"/>
            <native:side-nav-item id="nav-geolocation" label="Geolocation" icon="map-pin" url="{{ route('geolocation') }}" active="{{ request()->routeIs('geolocation') }}"/>
            <native:horizontal-divider/>
            <native:side-nav-item id="nav-profile" label="Profile" icon="user" url="{{ route('profile') }}" active="{{ request()->routeIs('profile') }}"/>
        </native:side-nav>
        <native:bottom-nav>
            <native:bottom-nav-item
                id="home"
                icon="description"
                label="{{__('Reports')}}"
                url="/reports"
                active="{{request()->routeIs('home')}}"
            />
            <native:bottom-nav-item
                id="add"
                icon="plus"
                class="size-18"
                label="{{__('New')}}"
                url="/reports/create"
{{--                badge="10"--}}
                active="{{request()->routeIs('reports.add')}}"
            />
            <native:bottom-nav-item
                id="settings"
                icon="settings"
                label="{{__('Settings')}}"
                url="/settings"
                active="{{request()->routeIs('settings')}}"
            />
        </native:bottom-nav>
    @endif

    <main class="px-4 {{\Native\Mobile\Facades\System::isAndroid() ? 'py-4' : 'py-15'}}">
{{--        <livewire:ui.network-monitor />--}}
        {{ $slot }}
    </main>

    @fluxScripts
    <flux:toast />
</body>
</html>

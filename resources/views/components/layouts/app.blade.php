@php use Native\Mobile\Edge\Edge; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')

</head>
<body id="app_body" class="bg-white dark:bg-zinc-950 min-h-screen relative">
{{--nativephp-safe-area--}}

    @if(!blank(\Native\Mobile\Facades\SecureStorage::get('api_token')) && auth()->check())

        @if(empty($link_back))
        <native:top-bar title="{{ $title ?? config('app.name') }}" :show-navigation-icon="true" text-color="#F26E36">
            @if(request()->routeIs('home'))
                <native:top-bar-action id="refresh-action" label="Refresh data" icon="refresh" url="{{ route('reports.refresh') }}"/>
            @endif
            <native:top-bar-action id="profile-action" label="Home" icon="user" url="{{ route('profile') }}"/>
                @if(\Native\Mobile\Facades\SecureStorage::get('device_online'))
                <native:top-bar-action id="network-status" label="Network" icon="chart" />
                @endif
        </native:top-bar>
        @else
        <native:top-bar title="{{$title ?? ''}}" :show-navigation-icon="false" text-color="#F26E36">
            <native:top-bar-action id="profile-action" label="Home" icon="back" url="{{ $link_back ?? request()->header('referer') }}"/>
            @if(\Native\Mobile\Facades\SecureStorage::get('device_online'))
            <native:top-bar-action id="network-status" label="Network" icon="chart" />
            @endif
        </native:top-bar>
        @endif


        @if(empty($link_back))
        <native:side-nav gestures_enabled="{{(\Native\Mobile\Facades\System::isIos()) ? request()->routeIs('home'):null}}">
            <native:side-nav-header
                title="Welcome"
                subtitle="{{auth()->user()->name ?? ''}}"
                icon="info"
                :show-close-button="true"
                :pinned="true"
            />
{{--            <native:side-nav-item id="nav-home" label="Home" icon="home" url="{{ route('home') }}" active="{{ request()->routeIs('home') }}"/>--}}
            <native:side-nav-item id="nav-geolocation" label="Geolocation" icon="map-pin" url="/geolocation" active="{{ request()->routeIs('geolocation') }}"/>
            <native:horizontal-divider/>
            <native:side-nav-item id="nav-profile" label="Profile" icon="user" url="/profile" active="{{ request()->routeIs('profile') }}"/>
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
                containerColor="#FF5C00"
                text-color="#F26E36"
                label="{{__('New')}}"
                url="/reports/create"
                active="{{request()->routeIs('reports.create')}}"
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
{{--        <native:fab--}}
{{--            icon="plus"--}}
{{--            size="regular"--}}
{{--            position="center"--}}
{{--            containerColor="#FF5C00"--}}
{{--            contentColor="#FFF"--}}
{{--            :bottomOffset="0"--}}
{{--            :cornerRadius="50"--}}
{{--            :elevation="0"--}}
{{--            :url="route('reports.create')"--}}
{{--        />--}}
      @endif
    <main @class(["animate-[slideInFromRight_0.3s_ease-out] ",
        'py-0 px-0' => \Native\Mobile\Facades\System::isAndroid(),
        'py-0 px-0' => \Native\Mobile\Facades\System::isIos(),
        'py-0 px-0' => !empty($link_back)
        ])>
        <livewire:ui.network-monitor />
        {{ $slot }}
    </main>



    @fluxScripts
    <flux:toast />
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        @vite(['app/Modules/Core/Resources/CSS/app.css', 'app/Modules/Core/Resources/JS/app.js']),
    </head>
    <body class="min-h-screen font-sans antialiased bg-base-200">

        {{-- NAVBAR mobile only --}}
        <x-nav sticky full-width>
            <x-slot:brand>
                <label for="main-drawer" class="lg:hidden me-3">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>
                <x-app-logo />
            </x-slot:brand>
            <x-slot:actions>
                <x-theme-toggle />

                <x-dropdown right no-x-anchor>
                    <x-slot:trigger>
                        <button class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full {{ auth()->user()->avatar_path ? '' : 'bg-primary' }}">
                                @if (auth()->user()->avatar_path)
                                    <img src="{{ asset(auth()->user()->avatar_path) }}" alt="{{ auth()->user()->name }}" />
                                @else
                                    <div class="flex items-center justify-center h-full">
                                    <span class="text-primary-content text-sm font-medium">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                    </div>
                                @endif
                            </div>
                        </button>
                    </x-slot:trigger>

                    <li class="px-4 py-2 border-b border-base-300 mb-1">
                        <div class="font-semibold text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-base-content/70 truncate">{{ auth()->user()->email }}</div>
                    </li>
                    <x-menu-separator />

                    @if(Route::has('profile'))
                        <x-menu-item title="Profile" icon="o-user" link="{{ route('profile') }}" />
                    @endif

                    @if(Route::has('settings'))
                        <x-menu-item title="Settings" icon="o-cog-6-tooth" link="{{ route('settings') }}" />
                    @endif

                    <x-menu-separator />

                    <li>
                        <x-form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button
                                type="submit"
                                class="my-0.5 py-1.5 px-4 hover:text-inherit whitespace-nowrap w-full text-left flex items-center gap-2"
                            >
                                <x-icon name="o-power" class="w-4 h-4" />
                                <span>Logout</span>
                            </button>
                        </x-form>
                    </li>
                </x-dropdown>
            </x-slot:actions>
        </x-nav>

        {{-- MAIN --}}
        <x-main with-nav full-width>
            {{-- SIDEBAR --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

                <x-menu activate-by-route>

                    <x-menu-item title="Hello" icon="o-sparkles" link="/" />
                    {{--                <x-menu-item title="Departments" icon="o-building-office-2" link="{{ route('tickets.departments.index') }}" />--}}
                    {{--                <x-menu-item title="Tags" icon="o-tag" link="{{ route('tickets.tags.index') }}" />--}}
                    {{--                <x-menu-item title="Canned Responses" link="{{ route('tickets.canned-responses.index') }}" />--}}

                    @if(Route::has('settings'))
                        <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                            @if(Route::has('settings.wifi'))
                                <x-menu-item title="Wifi" icon="o-wifi" link="{{ route('settings.wifi') }}" />
                            @endif
                            @if(Route::has('settings.archives'))
                                <x-menu-item title="Archives" icon="o-archive-box" link="{{ route('settings.archives') }}" />
                            @endif
                        </x-menu-sub>
                    @endif
                </x-menu>
            </x-slot:sidebar>

            {{-- The `$slot` goes here --}}
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>
        </x-main>

        {{--  TOAST area --}}
        <x-toast />
    </body>
</html>

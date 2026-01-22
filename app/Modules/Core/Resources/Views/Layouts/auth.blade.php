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
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="max-w-md w-full bg-base-100 rounded-2xl shadow-xl p-8">
                <div class="flex flex-col items-center mb-6">
                    <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                        <x-icon name="o-envelope" class="w-10 h-10 text-primary-content" />
                    </div>
                </div>
                {{ $slot }}
            </div>
        </div>
        <x-toast />
    </body>
</html>

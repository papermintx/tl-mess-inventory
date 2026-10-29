<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-base-content bg-base-200 relative min-h-screen">
        {{-- Background Image --}}
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <img 
                src="{{ asset('images/mess-bg.jpg') }}" 
                class="w-full h-full object-cover opacity-15 scale-105" 
                alt="Background Mess" 
                onerror="this.style.display='none'"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-base-200/70 via-base-200/90 to-base-200"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <div class="mb-2">
                <a href="/" wire:navigate class="inline-block">
                    <x-application-logo class="w-20 h-20" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-6 py-6 bg-base-100 shadow-xl rounded-2xl border border-base-300/60 backdrop-blur-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200 text-base-content relative selection:bg-primary selection:text-white">

    {{-- 1. FIXED BACKGROUND IMAGE DENGAN OPACITY LEMBUT --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        {{-- Foto Pantai / Depan Mess --}}
        <img 
            src="{{ asset('images/mess-bg.jpg') }}" 
            class="w-full h-full object-cover opacity-15 scale-105" 
            alt="Background Mess" 
            onerror="this.style.display='none'"
        />
        {{-- Overlay Gradasi Halus --}}
        <div class="absolute inset-0 bg-gradient-to-b from-base-200/70 via-base-200/90 to-base-200"></div>
    </div>

    {{-- 2. KONTEN APLIKASI (Z-INDEX DI ATAS BACKGROUND) --}}
    <div class="relative z-10 flex flex-col min-h-screen">
        {{-- NAVBAR MOBILE --}}
        <x-nav sticky class="lg:hidden glass-card border-b border-base-300">
            <x-slot:brand>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/tanjung-lesung.png') }}" class="w-8 h-8 object-contain" alt="Logo" onerror="this.src='https://placehold.co/100x100?text=TL'" />
                    <span class="font-bold text-sm">Tanjung Lesung</span>
                </div>
            </x-slot:brand>
            <x-slot:actions>
                <label for="main-drawer" class="lg:hidden me-3">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>
            </x-slot:actions>
        </x-nav>

        {{-- MAIN --}}
            <x-main full-width>
               {{-- SIDEBAR COLLAPSIBLE DENGAN EFEK GLASSMORPHISM --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="glass-card lg:bg-base-100/80 backdrop-blur-md border-r border-base-300/60">

                {{-- LOGO & BRAND (KLIK UNTUK TOGGLE COLLAPSE) --}}
                <div 
                    @click="toggle()" 
                    class="cursor-pointer flex items-center gap-3 px-4 py-4 select-none group border-b border-base-300/40"
                    title="Klik untuk mengecilkan / melebarkan menu"
                >
                    <img 
                        src="{{ asset('images/tanjung-lesung.png') }}" 
                        alt="Logo Tanjung Lesung" 
                        class="w-9 h-9 object-contain group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shrink-0" 
                        onerror="this.src='https://placehold.co/100x100?text=TL'"
                    />
                    <div class="hidden-when-collapsed transition-all overflow-hidden whitespace-nowrap">
                        <span class="font-bold text-sm tracking-tight block text-base-content group-hover:text-primary transition-colors">Tanjung Lesung</span>
                        <span class="text-[10px] text-base-content/60 block uppercase tracking-wider">Mess & Asset System</span>
                    </div>
                </div>

                {{-- MENU UTAMA --}}
                <x-menu activate-by-route class="mt-3">
                    <x-menu-item title="Showcase Mess" icon="o-home" link="/" />                
                    <x-menu-item title="Inventaris & Aset" icon="o-cube" link="/inventory" />
                    
                    <x-menu-separator />

                    @auth
                        <x-menu-item 
                            title="Logout" 
                            icon="o-arrow-left-on-rectangle" 
                            link="/logout" 
                            no-wire-navigate 
                            class="text-error hover:bg-error/10" 
                        />
                    @else
                        <x-menu-item 
                            title="Login Pengelola" 
                            icon="o-arrow-right-on-rectangle" 
                            link="/login" 
                            class="text-primary hover:bg-primary/10 font-semibold" 
                        />
                    @endauth
                </x-menu>
            </x-slot:sidebar>

            {{-- CONTENT SLOT --}}
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>
        </x-main>
    </div>
</body>
</html>
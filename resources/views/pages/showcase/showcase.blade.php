<!DOCTYPE html>
<html lang="id" data-theme="light" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mess Showcase & Asset Inventory - Tanjung Lesung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 text-base-content antialiased flex flex-col justify-between selection:bg-primary selection:text-white">

    {{-- 1. Navbar dengan Logo & Navigasi --}}
    @include('pages.showcase.partials.navbar')

    {{-- 2. Hero Section (Headline & Visual Mess) --}}
    @include('pages.showcase.partials.hero')

    {{-- 3. Ringkasan Metrik Statistik --}}
    @include('pages.showcase.partials.stats')

    {{-- 4. Fasilitas Standar Mess --}}
    @include('pages.showcase.partials.facilities')

    {{-- 5. Daftar Kamar & Area Mess (Database) --}}
    @include('pages.showcase.partials.rooms')

    {{-- 6. Footer --}}
    @include('pages.showcase.partials.footer')

    {{-- 7. Script Interaktif Filter & Modal --}}
    @include('pages.showcase.partials.scripts')

</body>
</html>
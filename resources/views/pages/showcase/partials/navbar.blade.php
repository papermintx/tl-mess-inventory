{{-- NAVBAR DENGAN LOGO TANJUNG LESUNG --}}
<nav class="sticky top-0 z-50 glass-card border-b border-base-300/80 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 group cursor-pointer">
            <img 
                src="{{ asset('images/tanjung-lesung.png') }}" 
                alt="Logo Tanjung Lesung" 
                class="w-10 h-10 object-contain group-hover:scale-105 transition-transform duration-300" 
                onerror="this.src='https://placehold.co/100x100?text=TL'"
            />
            <div>
                <span class="font-bold text-lg tracking-tight block group-hover:text-primary transition-colors">Mess Tanjung Lesung</span>
                <span class="text-xs text-base-content/60 block">Sistem Informasi & Aset Fasilitas</span>
            </div>
        </a>

        <div class="flex items-center gap-3">
            <a href="#fasilitas" class="btn btn-ghost btn-sm hover:text-primary transition-colors hidden md:inline-flex">Fasilitas</a>
            <a href="#kamar" class="btn btn-ghost btn-sm hover:text-primary transition-colors hidden sm:inline-flex">Kamar & Area</a>
            <a href="/inventory" class="btn btn-ghost btn-sm hover:text-primary transition-colors inline-flex gap-1.5 font-semibold">
                <x-icon name="o-table-cells" class="w-4 h-4 text-primary" />
                Daftar Inventaris
            </a>
        </div>
    </div>
</nav>


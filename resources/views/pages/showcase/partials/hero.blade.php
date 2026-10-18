{{-- HERO SECTION --}}
@php
    $heroBg = $heroBackgroundImage ?? (
        file_exists(public_path('images/sunset-hero.jpg'))
            ? asset('images/sunset-hero.jpg')
            : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2000&q=80'
    );
@endphp

<section class="relative py-20 lg:py-28 overflow-hidden">
    {{-- Background Image Layer (Pemandangan Golden Hour Sunset Pantai Tanjung Lesung) --}}
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none select-none">
        <img 
            src="{{ $heroBg }}" 
            alt="Pemandangan Sunset Golden Hour Tanjung Lesung" 
            class="w-full h-full object-cover object-center scale-105 motion-safe:animate-fade-in filter brightness-[0.92] contrast-[1.03]"
            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2000&q=80';"
        />

        {{-- Cinematic Dusk Scrim Overlays: Menyatukan warna & menjamin teks terbaca jelas tanpa bentrok --}}
        {{-- 1. Gradasi horizontal melindungi keterbacaan teks headline & deskripsi di sisi kiri --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 to-black/15 lg:to-transparent"></div>
        
        {{-- 2. Gradasi vertikal untuk ambience senja dan transisi mulus ke section bawah --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/25"></div>
        <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-base-100 to-transparent"></div>

        {{-- 3. Aksen rona hangat golden hour --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-amber-950/20 via-orange-950/10 to-transparent"></div>
    </div>

    {{-- Warm Ambient Glow Accents (Senada dengan cahaya matahari terbenam) --}}
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl pointer-events-none z-1"></div>
    <div class="absolute top-1/2 -right-24 w-96 h-96 bg-orange-500/20 rounded-full blur-3xl pointer-events-none z-1"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            {{-- Text Content --}}
            <div class="space-y-6 text-center lg:text-left">
                {{-- Badge Kategori Senada Sunset --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/40 backdrop-blur-md text-amber-300 text-xs font-semibold border border-amber-400/30 shadow-lg">
                    <x-icon name="o-sparkles" class="w-4 h-4 text-amber-400" />
                    Akomodasi Terstandarisasi & Terdata
                </div>

                {{-- Headline: Putih Elegan + Aksen Gradasi Emas Sunset --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white drop-shadow-md">
                    Mess Cowok <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-orange-400 drop-shadow-sm">
                        Tanjung Lesung
                    </span>
                </h1>

                {{-- Subtitle: Teks Hangat Kontras Tinggi & Terbaca Jelas --}}
                <p class="text-base sm:text-lg text-amber-50/90 max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal drop-shadow-sm">
                    Portal informasi akomodasi terpadu serta sistem inventarisasi kondisi fisik aset.
                </p>

                {{-- Tombol Aksi Senada Sunset --}}
                <div class="flex flex-wrap gap-4 justify-center lg:justify-start pt-2">
                    <a href="#fasilitas" class="btn btn-ghost bg-white/10 hover:bg-white/20 text-white border border-white/30 backdrop-blur-md px-6 hover:-translate-y-0.5 transition-all shadow-md">
                        Jelajahi Fasilitas
                    </a>
                </div>
            </div>

            {{-- Hero Visual Card (Landscape Aspect-Video 16:9) --}}
            <div class="relative animate-float">
                <div class="relative mx-auto rounded-3xl overflow-hidden isolate shadow-2xl border-4 border-white/20 bg-slate-900 aspect-video group cursor-pointer
                            transition-[rotate,scale,box-shadow] duration-500 ease-out
                            hover:-rotate-2 hover:scale-[1.02] ring-1 ring-black/20">
                    <x-image-loader 
                        src="images/main-photo.jpg" 
                        fallback="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=1200&q=80"
                        alt="Mess Cowok Tanjung Lesung" 
                        class="w-full h-full object-cover group-hover:scale-110" 
                    />
                    {{-- Overlay Teks --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent flex flex-col justify-end p-6 text-white pointer-events-none">
                        <span class="badge bg-amber-500 text-slate-950 border-none badge-sm mb-2 shadow-md font-bold w-fit">Tampak Area Mess</span>
                        <h3 class="text-xl font-bold">Lingkungan Nyaman & Terawat</h3>
                        <p class="text-xs text-white/80">Fasilitas pendukung optimal untuk produktivitas peserta magang.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

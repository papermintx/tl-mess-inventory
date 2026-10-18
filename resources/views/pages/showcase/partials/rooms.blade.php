{{-- DAFTAR KAMAR & AREA MESS DENGAN FOTO LANDSCAPE 16:9 --}}
<section id="kamar" class="py-20 bg-base-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold text-primary tracking-widest uppercase block mb-1">Tata Letak Lokasi</span>
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Kamar & Area Mess</h2>
            <p class="text-sm text-base-content/70 mt-2">Data riil unit ruangan yang dipantau melalui sistem inventaris.</p>
        </div>

        {{-- Grid Kartu Kamar / Ruangan (Rata Tengah / Centered Flex Layout) --}}
        <div class="flex flex-wrap justify-center gap-6">
            @forelse($rooms as $room)
                <div class="card bg-base-100 border border-base-300 shadow-sm hover-lift group overflow-hidden flex flex-col justify-between w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] xl:w-[calc(25%-18px)] max-w-sm">
                    <div>
                        {{-- Foto Ruangan Format Landscape (Aspect Video 16:9) --}}
                        <figure class="relative aspect-video w-full overflow-hidden bg-base-200">
                            <x-image-loader 
                                :src="$room->cover_image_url" 
                                :alt="$room->name" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" 
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent pointer-events-none"></div>

                            {{-- Badge Kode Kamar --}}
                            <div class="absolute top-3 left-3">
                                <span class="badge badge-neutral backdrop-blur-md text-white font-mono text-xs font-semibold shadow-sm">
                                    {{ $room->code }}
                                </span>
                            </div>

                            {{-- Badge Kapasitas Kamar --}}
                            <div class="absolute top-3 right-3">
                                <span class="badge badge-primary text-white font-semibold text-xs shadow-sm">
                                    {{ $room->capacity }} Orang
                                </span>
                            </div>
                        </figure>

                        {{-- Konten Informasi Kamar (Rata Tengah) --}}
                        <div class="p-5 space-y-2 text-center">
                            <h3 class="font-bold text-base group-hover:text-primary transition-colors line-clamp-1" title="{{ $room->name }}">
                                {{ $room->name }}
                            </h3>
                            <p class="text-xs text-base-content/70 line-clamp-2 leading-relaxed">
                                {{ $room->description }}
                            </p>
                        </div>
                    </div>

                    {{-- Footer Kartu: Jumlah Aset Terdaftar (Rata Tengah) --}}
                    <div class="px-5 pb-5 pt-0">
                        <div class="divider my-2"></div>
                        <div class="flex items-center justify-center gap-1.5 text-xs text-base-content/70">
                            <span>Aset Terdaftar:</span>
                            <span class="font-bold text-primary flex items-center gap-1">
                                <x-icon name="o-cube" class="w-3.5 h-3.5" />
                                {{ $room->assets_count }} Barang
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-base-content/60">
                    <p class="text-sm">Belum ada data ruangan terdaftar.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

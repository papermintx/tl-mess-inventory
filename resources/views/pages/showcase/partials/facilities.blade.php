{{-- FASILITAS STANDAR MESS --}}
<section id="fasilitas" class="py-20 bg-base-200/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold text-primary tracking-widest uppercase block mb-1">Kenyamanan Utama</span>
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Fasilitas Standar Mess</h2>
            <p class="text-sm text-base-content/70 mt-2">Disediakan untuk menunjang kenyamanan, produktivitas kerja, dan istirahat harian peserta magang/pekerja.</p>
        </div>

        {{-- Grid Kartu Fasilitas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($facilities as $facility)
                <div class="card bg-base-100 shadow-sm border border-base-300 hover-lift overflow-hidden group flex flex-col justify-between">
                    <div>
                        {{-- Foto Landscape (Aspect Video 16:9) --}}
                        <figure class="relative aspect-video w-full overflow-hidden bg-base-200">
                            <x-image-loader 
                                :src="$facility->cover_image_url" 
                                :alt="$facility->name" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" 
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                            
                            @if($facility->badge)
                                <div class="absolute top-3 right-3">
                                    <span class="badge badge-primary text-white text-xs font-semibold shadow-md gap-1">
                                        <x-icon name="o-sparkles" class="w-3 h-3" />
                                        {{ $facility->badge }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute bottom-3 left-3 w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg">
                                <x-icon :name="$facility->icon ?: 'o-home-modern'" class="w-5 h-5" />
                            </div>
                        </figure>

                        {{-- Konten Deskripsi Fasilitas --}}
                        <div class="p-6 space-y-3">
                            <h3 class="text-lg font-bold group-hover:text-primary transition-colors">
                                {{ $facility->name }}
                            </h3>
                            <p class="text-sm text-base-content/70 leading-relaxed">
                                {{ $facility->description }}
                            </p>
                        </div>
                    </div>

                    {{-- Tags Fitur Fasilitas --}}
                    @if(!empty($facility->tags))
                        <div class="px-6 pb-6 pt-0">
                            <div class="flex flex-wrap gap-1.5 pt-2 border-t border-base-300/60">
                                @foreach($facility->tags as $tag)
                                    <span class="badge badge-ghost text-xs font-medium border border-base-300/80">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-base-content/60">
                    <p class="text-sm">Belum ada fasilitas terdaftar.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- MODAL DETAIL & FOTO (KHUSUS LIHAT DETAIL DAN FOTO SAJA) --}}
<x-modal wire:model="modalDetail" title="Detail & Dokumentasi Fisik Aset" subtitle="Inspeksi foto kondisi barang lapangan" separator class="backdrop-blur">
    @if($selectedAsset)
        <div class="space-y-5">
            {{-- Info Ringkas Aset --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-base-200/60 p-4 rounded-2xl border border-base-300 text-xs">
                <div>
                    <span class="text-base-content/60 block">Kode Aset:</span>
                    <span class="font-bold text-sm">{{ $selectedAsset->code }}</span>
                </div>
                <div>
                    <span class="text-base-content/60 block">Nama Barang:</span>
                    <span class="font-semibold text-sm">{{ $selectedAsset->name }}</span>
                </div>
                <div>
                    <span class="text-base-content/60 block">Lokasi Ruangan:</span>
                    <span class="font-medium text-sm">{{ $selectedAsset->room->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-base-content/60 block">Kondisi Saat Ini:</span>
                    <x-badge :value="strtoupper(str_replace('_', ' ', $selectedAsset->status))" :class="$selectedAsset->status_color . ' badge-xs mt-1'" />
                </div>
            </div>

            {{-- Catatan Kerusakan / Keterangan --}}
            @if($selectedAsset->notes)
                <div class="p-3 bg-warning/10 border border-warning/20 rounded-xl text-xs text-base-content/80">
                    <span class="font-bold text-warning block mb-1">Catatan Tambahan:</span>
                    {{ $selectedAsset->notes }}
                </div>
            @endif

            {{-- Galeri Foto Bukti Lapangan --}}
            <div>
                <h4 class="font-bold text-sm mb-3 flex items-center justify-between">
                    <span>Galeri Foto Bukti Lapangan</span>
                    <span class="text-xs font-normal text-base-content/60">({{ $selectedAsset->photos->count() }} Foto)</span>
                </h4>

                @if($selectedAsset->photos->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-72 overflow-y-auto pr-1">
                        @foreach($selectedAsset->photos as $photo)
                            <div class="rounded-xl overflow-hidden border border-base-300 bg-base-200 shadow-sm">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-40 object-cover" />
                                
                                {{-- Keterangan Foto --}}
                                <div class="p-2.5 bg-base-100 text-xs space-y-1">
                                    <p class="font-medium text-base-content/90 line-clamp-1">{{ $photo->condition_note }}</p>
                                    <span class="text-[10px] text-base-content/50 block">Diunggah oleh: {{ $photo->uploader->name ?? 'Admin' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 border-2 border-dashed border-base-300 rounded-2xl text-base-content/50 text-xs space-y-1">
                        <x-icon name="o-photo" class="w-10 h-10 mx-auto opacity-30" />
                        <p class="font-medium">Belum ada dokumentasi foto untuk aset ini.</p>
                        @can('manage-assets')
                            <p class="text-[11px] text-base-content/40">Foto dapat diunggah melalui tombol Ubah Data (Edit Aset).</p>
                        @else
                            <p class="text-[11px] text-base-content/40">Dokumentasi foto fisik akan diunggah oleh Administrator Pengelola.</p>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ACTIONS: HANYA TOMBOL TUTUP --}}
    <x-slot:actions>
        @can('manage-assets')
            @if($selectedAsset)
                <x-button 
                    label="Ubah Data Aset Ini" 
                    icon="o-pencil-square" 
                    class="btn-primary btn-sm" 
                    wire:click="edit({{ $selectedAsset->id }})" 
                    @click="$wire.modalDetail = false" 
                />
            @endif
        @endcan
        <x-button label="Tutup" class="btn-outline btn-sm" @click="$wire.modalDetail = false" />
    </x-slot:actions>
</x-modal>

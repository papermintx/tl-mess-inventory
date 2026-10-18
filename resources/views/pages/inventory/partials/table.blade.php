{{-- TABEL DATA INVENTARIS (RESPONSIF & PROPORSIONAL MODE DESKTOP) --}}
<x-card shadow class="bg-base-100/90 backdrop-blur-sm border border-base-300 overflow-hidden !p-0">
    <div class="overflow-x-auto w-full">
        <x-table 
            :headers="$headers" 
            :rows="$assets" 
            :sort-by="$sortBy" 
            striped
            with-pagination
            class="w-full table-md [&_th]:bg-base-200/80 [&_th]:py-3.5 [&_th]:text-xs [&_th]:tracking-wider [&_th]:text-base-content/70 [&_th]:border-b [&_th]:border-base-300 [&_td]:py-3 [&_td]:align-middle [&_tr:hover]:bg-base-200/40"
        >
            {{-- 1. Kolom Kode Aset (Rapi 1 Baris, Monospace Badge) --}}
            @scope('cell_code', $asset)
                <div class="whitespace-nowrap">
                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-md bg-base-200/80 text-base-content/90 border border-base-300/70 shadow-2xs">
                        {{ $asset->code }}
                    </span>
                </div>
            @endscope

            {{-- 2. Kolom Nama Barang (Truncate Otomatis dengan Ellipsis ...) --}}
            @scope('cell_name', $asset)
                <div class="flex items-center gap-2.5 py-0.5 max-w-[230px] md:max-w-[260px] overflow-hidden">
                    @if($asset->photos->isNotEmpty())
                        <div class="w-9 h-9 rounded-lg overflow-hidden border border-primary/20 shrink-0 shadow-2xs group-hover:scale-105 transition-transform bg-base-200">
                            <img src="{{ $asset->photo_url }}" alt="{{ $asset->name }}" class="w-full h-full object-cover" />
                        </div>
                    @else
                        <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary border border-primary/20 flex items-center justify-center shrink-0">
                            <x-icon name="o-cube" class="w-4 h-4 text-primary/70" />
                        </div>
                    @endif
                    <div class="min-w-0 flex-1 overflow-hidden">
                        <span class="font-bold text-sm text-base-content block truncate group-hover:text-primary transition-colors" title="{{ $asset->name }}">
                            {{ $asset->name }}
                        </span>
                        @if($asset->notes)
                            <span class="text-[11px] text-base-content/55 block truncate" title="{{ $asset->notes }}">
                                {{ $asset->notes }}
                            </span>
                        @endif
                    </div>
                </div>
            @endscope

            {{-- 3. Kolom Lokasi Ruangan --}}
            @scope('cell_room.name', $asset)
                <div class="flex items-center gap-1.5 whitespace-nowrap max-w-[180px] overflow-hidden">
                    <x-icon name="o-map-pin" class="w-3.5 h-3.5 text-primary/80 shrink-0" />
                    <span class="text-xs font-semibold text-base-content/85 truncate" title="{{ $asset->room->name ?? '-' }}">
                        {{ $asset->room->name ?? '-' }}
                    </span>
                </div>
            @endscope

            {{-- 4. Kolom Kategori --}}
            @scope('cell_category', $asset)
                <div class="flex justify-center whitespace-nowrap">
                    <span class="badge badge-ghost badge-sm text-xs font-medium text-base-content/80 border border-base-300/80">
                        {{ $asset->category }}
                    </span>
                </div>
            @endscope

            {{-- 5. Kolom Jumlah / Quantity --}}
            @scope('cell_quantity', $asset)
                <div class="flex justify-center whitespace-nowrap">
                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded-md bg-base-200 text-base-content/90 border border-base-300/50">
                        {{ $asset->quantity }}
                    </span>
                </div>
            @endscope

            {{-- 6. Kolom Kondisi Fisik (Status Badge Proporsional dengan Titik Indikator) --}}
            @scope('cell_status', $asset)
                <div class="flex justify-center whitespace-nowrap">
                    @if($asset->status === 'bagus')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-success/15 text-success border border-success/30 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-success"></span>
                            Bagus
                        </span>
                    @elseif($asset->status === 'perlu_perbaikan')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-warning/20 text-warning-content border border-warning/40 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span>
                            Perlu Perbaikan
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-error/15 text-error border border-error/30 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-error"></span>
                            Rusak
                        </span>
                    @endif
                </div>
            @endscope

            {{-- 7. Kolom Aksi / Inspeksi (Tepat di bawah Header Aksi) --}}
            @scope('cell_actions', $asset)
                <div class="flex items-center justify-center gap-1 whitespace-nowrap">
                    <x-button 
                        icon="o-eye" 
                        wire:click="showDetail({{ $asset->id }})" 
                        class="btn-ghost btn-xs text-info hover:bg-info/10" 
                        tooltip="Detail & Galeri Foto" 
                    />

                    @can('manage-assets')
                        <x-button 
                            icon="o-pencil-square" 
                            wire:click="edit({{ $asset->id }})" 
                            class="btn-ghost btn-xs text-warning hover:bg-warning/10" 
                            tooltip="Ubah Data Aset" 
                        />
                        <x-button 
                            icon="o-trash" 
                            wire:click="delete({{ $asset->id }})" 
                            wire:confirm="Konfirmasi Audit Keamanan: Apakah Anda yakin ingin menghapus aset '{{ $asset->name }}' ({{ $asset->code }}) dari inventaris? Tindakan ini akan dicatat dalam log audit sistem." 
                            class="btn-ghost btn-xs text-error hover:bg-error/10" 
                            tooltip="Hapus Aset" 
                        />
                    @endcan
                </div>
            @endscope

            {{-- Slot Kosong Jika Tidak Ada Data Ditemukan --}}
            <x-slot:empty>
                <div class="py-12 text-center space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-base-200/80 flex items-center justify-center text-base-content/40 border border-base-300">
                        <x-icon name="o-inbox" class="w-7 h-7" />
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-base-content/80">Tidak ada aset ditemukan</h4>
                        <p class="text-xs text-base-content/60 mt-1">Coba sesuaikan kata kunci pencarian atau filter status dan ruangan.</p>
                    </div>
                </div>
            </x-slot:empty>
        </x-table>
    </div>
</x-card>

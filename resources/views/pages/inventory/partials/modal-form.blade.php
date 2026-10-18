{{-- MODAL FORM TAMBAH / EDIT ASET (LENGKAP DENGAN UPLOAD FOTO) --}}
@can('manage-assets')
    <x-modal wire:model="modalForm" :title="$editingAssetId ? 'Ubah Data Aset' : 'Tambah Aset Baru'" separator>
        <x-form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Kode Aset" wire:model="code" readonly />
                <x-select label="Lokasi Ruangan (1 Lantai)" wire:model="room_code" :options="$rooms" option-value="code" option-label="name" placeholder="Pilih Ruangan" />
            </div>

            <x-input label="Nama Barang" wire:model="name" placeholder="Contoh: AC Sharp 1 PK" />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input label="Kategori" wire:model="category" placeholder="Elektronik, Furniture..." />
                <x-input label="Jumlah (Qty)" type="number" wire:model="quantity" min="1" />
                <x-select label="Kondisi Fisik" wire:model="status" :options="[
                    ['id' => 'bagus', 'name' => 'Bagus'],
                    ['id' => 'perlu_perbaikan', 'name' => 'Perlu Perbaikan'],
                    ['id' => 'rusak', 'name' => 'Rusak']
                ]" />
            </div>

            <x-textarea label="Catatan Tambahan / Spesifikasi" wire:model="notes" placeholder="Catatan kondisi spesifik jika ada..." rows="2" />

            {{-- FORM UPLOAD FOTO FISIK --}}
            <div class="divider text-xs text-base-content/50 uppercase my-2">Unggah Dokumentasi Foto Fisik</div>

            <div class="space-y-3 p-4 bg-base-200/50 rounded-2xl border border-base-300">
                <x-file 
                    wire:model="newPhoto" 
                    label="Pilih Foto Barang" 
                    hint="Format: JPG, PNG, WEBP (Maks 5MB) - Opsional" 
                    accept="image/*" 
                    icon="o-camera" 
                />

                {{-- Indikator Loading Saat File Diproses --}}
                <div wire:loading wire:target="newPhoto" class="text-xs text-primary flex items-center gap-2">
                    <span class="loading loading-spinner loading-xs"></span>
                    Sedang memproses file gambar...
                </div>

                {{-- Preview Foto Instan & Input Catatan Foto --}}
                @if ($newPhoto)
                    <div class="mt-2 flex items-center gap-3 bg-base-100 p-2.5 rounded-xl border border-base-300">
                        <img src="{{ $newPhoto->temporaryUrl() }}" class="w-20 h-20 object-cover rounded-lg border border-base-300 shadow-xs shrink-0" />
                        <div class="flex-1">
                            <x-input 
                                label="Catatan Singkat Foto" 
                                wire:model="photo_note" 
                                placeholder="Misal: Tampak depan AC / retak pada bodi" 
                            />
                        </div>
                    </div>
                @endif
            </div>

            {{-- ACTIONS: BATAL & SIMPAN --}}
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalForm = false" />
                <x-button label="Simpan Aset" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
@endcan

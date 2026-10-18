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

            {{-- DAFTAR FOTO YANG SUDAH TERSIMPAN (KHUSUS MODE EDIT ASET) --}}
            @if($editingAsset)
                <div class="divider text-xs text-base-content/50 uppercase my-2">Foto Fisik Tersimpan ({{ $editingAsset->photos->count() }})</div>
                
                @if($editingAsset->photos->isNotEmpty())
                    <div class="space-y-2 p-3 bg-base-200/50 rounded-2xl border border-base-300">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($editingAsset->photos as $photo)
                                <div class="relative rounded-xl overflow-hidden border border-base-300 bg-base-100 shadow-sm flex flex-col justify-between">
                                    <div class="relative aspect-video w-full overflow-hidden bg-base-200">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-full object-cover" />
                                        
                                        {{-- Tombol Hapus Foto Langsung Tanpa Dialog Konfirmasi --}}
                                        <div class="absolute top-2 right-5 z-10">
                                            <x-button 
                                                icon="o-trash" 
                                                wire:click="deletePhoto({{ $photo->id }})" 
                                                class="btn-error btn-xs btn-circle shadow-lg hover:scale-110 active:scale-95 transition-transform" 
                                            />
                                        </div>
                                    </div>
                                    
                                    @if($photo->condition_note)
                                        <div class="p-1.5 text-[10px] text-base-content/70 truncate bg-base-100 border-t border-base-300" title="{{ $photo->condition_note }}">
                                            {{ $photo->condition_note }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-3 text-center bg-base-200/40 rounded-xl border border-dashed border-base-300 text-xs text-base-content/60">
                        Aset ini belum memiliki foto fisik tersimpan. Anda dapat mengunggah foto baru di bawah ini.
                    </div>
                @endif
            @endif

            {{-- FORM UPLOAD FOTO FISIK --}}
            <div class="divider text-xs text-base-content/50 uppercase my-2">{{ $editingAsset ? 'Tambah Foto Fisik Baru' : 'Unggah Dokumentasi Foto Fisik' }}</div>

            <div class="space-y-3 p-4 bg-base-200/50 rounded-2xl border border-base-300">
                <x-file 
                    wire:model="newPhoto" 
                    label="Pilih Foto Barang" 
                    hint="Format: JPEG, JPG, PNG, WEBP (Maks 5MB) - Opsional" 
                    accept="image/jpeg,image/png,image/webp,image/jpg,.jpeg,.jpg,.png,.webp" 
                    icon="o-camera" 
                />

                {{-- Indikator Loading Saat File Diproses --}}
                <div wire:loading wire:target="newPhoto" class="text-xs text-primary flex items-center gap-2">
                    <span class="loading loading-spinner loading-xs"></span>
                    Sedang memproses file gambar...
                </div>

                {{-- Preview Foto Instan & Input Catatan Foto --}}
                @if ($newPhoto)
                    <div class="mt-2 flex items-center justify-between gap-3 bg-base-100 p-2.5 rounded-xl border border-base-300">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <img src="{{ $newPhoto->temporaryUrl() }}" class="w-16 h-16 object-cover rounded-lg border border-base-300 shadow-xs shrink-0" />
                            <div class="flex-1 min-w-0">
                                <x-input 
                                    label="Catatan Singkat Foto" 
                                    wire:model="photo_note" 
                                    placeholder="Misal: Tampak depan AC / retak pada bodi" 
                                />
                            </div>
                        </div>
                        <x-button 
                            icon="o-trash" 
                            wire:click="$set('newPhoto', null)" 
                            class="btn-error btn-sm btn-circle text-white shadow-xs shrink-0" 
                            tooltip="Batal / Hapus Foto Pilihan" 
                        />
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

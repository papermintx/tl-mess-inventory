<?php

use App\Models\Asset;
use App\Models\AssetPhoto;
use App\Models\Room;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new #[Title('Inventaris Aset - Mess Tanjung Lesung')] class extends Component {
    use Toast, WithPagination, WithFileUploads;

    // Filter & Search
    public string $search = '';
    public string $filterRoom = '';
    public string $filterStatus = '';
    public array $sortBy = ['column' => 'id', 'direction' => 'desc'];

    // Modal Form (Tambah / Edit Aset)
    public bool $modalForm = false;
    public ?int $editingAssetId = null;

    #[Rule('required|string|max:50')]
    public string $room_code = '';

    #[Rule('required|string|max:50')]
    public string $code = '';

    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('required|string|max:50')]
    public string $category = 'Elektronik';

    #[Rule('required|in:bagus,perlu_perbaikan,rusak')]
    public string $status = 'bagus';

    #[Rule('required|integer|min:1')]
    public int $quantity = 1;

    public ?string $notes = '';

    // Modal Detail & Upload Foto
    public bool $modalDetail = false;
    public ?Asset $selectedAsset = null;

    #[Rule('nullable|image|mimes:jpeg,jpg,png,webp|max:5120')]
    public $newPhoto = null;

    #[Rule('nullable|string|max:255')]
    public string $photo_note = '';

    public function headers(): array
    {
        $isAdmin = auth()->user()?->isAdmin();

        return [
            ['key' => 'code', 'label' => 'KODE ASET', 'class' => 'font-bold w-28 whitespace-nowrap text-left'],
            ['key' => 'name', 'label' => 'NAMA BARANG', 'class' => 'font-bold w-64 max-w-[260px] text-left'],
            ['key' => 'room.name', 'label' => 'LOKASI RUANGAN', 'class' => 'font-bold w-44 max-w-[190px] text-left'],
            ['key' => 'category', 'label' => 'KATEGORI', 'class' => 'font-bold w-24 text-center whitespace-nowrap'],
            ['key' => 'quantity', 'label' => 'JML', 'class' => 'font-bold w-16 text-center whitespace-nowrap'],
            ['key' => 'status', 'label' => 'KONDISI FISIK', 'class' => 'font-bold w-36 text-center whitespace-nowrap'],
            ['key' => 'actions', 'label' => $isAdmin ? 'AKSI PENGELOLA' : 'INSPEKSI ASET', 'class' => 'font-bold w-32 text-center whitespace-nowrap', 'sortable' => false],
        ];
    }

    public function messages(): array
    {
        return [
            'newPhoto.image' => 'File harus berupa gambar yang valid.',
            'newPhoto.mimes' => 'Format foto harus berupa JPEG, JPG, PNG, atau WEBP.',
            'newPhoto.max' => 'Ukuran foto maksimal adalah 5 MB.',
            'newPhoto.uploaded' => 'File foto gagal diunggah. Pastikan ukuran file maksimal 5 MB dan server sudah dimulai ulang.',
        ];
    }

    public function create(): void
    {
        if (!auth()->check()) {
            $this->warning('Silakan login terlebih dahulu untuk menambah atau mengelola aset.', position: 'toast-top');
            $this->redirectRoute('login', navigate: true);

            return;
        }

        // Standar Bank: Hanya Administrator Pengelola yang berwenang menambah aset
        abort_if(!auth()->user()?->isAdmin(), 403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk menambahkan aset inventaris. Hak ini hanya dimiliki oleh Administrator Pengelola sesuai standar perbankan.');

        $this->reset(['editingAssetId', 'room_code', 'code', 'name', 'category', 'status', 'quantity', 'notes', 'newPhoto', 'photo_note']);
        $this->code = 'AST-TL-' . str_pad((string)(Asset::max('id') + 1), 3, '0', STR_PAD_LEFT);
        $this->modalForm = true;
    }

    public function edit(int $id): void
    {
        if (!auth()->check()) {
            $this->warning('Silakan login terlebih dahulu untuk mengubah data aset.', position: 'toast-top');
            $this->redirectRoute('login', navigate: true);

            return;
        }

        // Standar Bank: Hanya Administrator Pengelola yang berwenang mengubah aset
        abort_if(!auth()->user()?->isAdmin(), 403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengubah data aset inventaris.');

        $asset = Asset::findOrFail($id);
        $this->editingAssetId = $asset->id;
        $this->room_code = $asset->room_code;
        $this->code = $asset->code;
        $this->name = $asset->name;
        $this->category = $asset->category;
        $this->status = $asset->status;
        $this->quantity = $asset->quantity;
        $this->notes = $asset->notes;
        $this->reset(['newPhoto', 'photo_note']);

        $this->modalForm = true;
    }

    public function save(): void
    {
        if (!auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        // Standar Bank: Validasi ketat otorisasi Administrator Pengelola
        abort_if(!auth()->user()?->isAdmin(), 403, 'Akses Ditolak: Hanya Administrator Pengelola yang berwenang menyimpan atau memperbarui data inventaris.');

        $data = $this->validate([
            'room_code' => ['required', 'string', ValidationRule::in(Room::codes())],
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'status' => 'required|in:bagus,perlu_perbaikan,rusak',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'newPhoto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'photo_note' => 'nullable|string|max:255',
        ]);

        if ($this->editingAssetId) {
            $asset = Asset::findOrFail($this->editingAssetId);
            $asset->update([
                'room_code' => $data['room_code'],
                'code' => $data['code'],
                'name' => $data['name'],
                'category' => $data['category'],
                'status' => $data['status'],
                'quantity' => $data['quantity'],
                'notes' => $data['notes'],
            ]);

            // Audit Trail Bank: Catat pembaruan aset
            Log::info('AUDIT INVENTORY UPDATE: Data aset diperbarui oleh administrator.', [
                'asset_id' => $asset->id,
                'code' => $asset->code,
                'name' => $asset->name,
                'status' => $asset->status,
                'user_id' => auth()->id(),
                'user_email' => auth()->user()?->email,
            ]);

            $this->success('Data aset berhasil diperbarui!');
        } else {
            $asset = Asset::create([
                'room_code' => $data['room_code'],
                'code' => $data['code'],
                'name' => $data['name'],
                'category' => $data['category'],
                'status' => $data['status'],
                'quantity' => $data['quantity'],
                'notes' => $data['notes'],
            ]);

            // Audit Trail Bank: Catat pendaftaran aset baru
            Log::info('AUDIT INVENTORY CREATE: Aset baru didaftarkan oleh administrator.', [
                'asset_id' => $asset->id,
                'code' => $asset->code,
                'name' => $asset->name,
                'status' => $asset->status,
                'user_id' => auth()->id(),
                'user_email' => auth()->user()?->email,
            ]);

            $this->success('Aset baru berhasil ditambahkan!');
        }

        // SIMPAN FOTO FISIK JIKA ADA FILE YANG DIUNGGAH DI MODAL FORM
        if ($this->newPhoto) {
            $path = $this->newPhoto->store('assets', 'public');

            AssetPhoto::create([
                'asset_id' => $asset->id,
                'photo_path' => $path,
                'condition_note' => $this->photo_note ?: 'Dokumentasi foto fisik (' . ucfirst(str_replace('_', ' ', $asset->status)) . ')',
                'uploaded_by' => auth()->id(),
            ]);

            Log::info('AUDIT INVENTORY PHOTO: Foto fisik baru diunggah.', [
                'asset_id' => $asset->id,
                'user_id' => auth()->id(),
            ]);
        }

        $this->modalForm = false;
        $this->reset(['newPhoto', 'photo_note']);
    }

    public function delete(int $id): void
    {
        if (!auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        // Standar Bank: Hanya Administrator Pengelola yang berwenang menghapus aset
        abort_if(!auth()->user()?->isAdmin(), 403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk menghapus aset inventaris.');

        $asset = Asset::findOrFail($id);
        $code = $asset->code;
        $name = $asset->name;

        // Hapus file fisik foto terkait dari storage
        foreach ($asset->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }
        $asset->delete();

        // Audit Trail Bank: Catat penghapusan aset
        Log::warning('AUDIT INVENTORY DELETE: Aset telah dihapus dari inventaris.', [
            'asset_id' => $id,
            'code' => $code,
            'name' => $name,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
        ]);

        $this->success('Aset berhasil dihapus dari sistem.');
    }

    public function showDetail(int $id): void
    {
        $this->selectedAsset = Asset::with(['photos.uploader'])->findOrFail($id);
        $this->reset(['newPhoto', 'photo_note']);
        $this->modalDetail = true;
    }

    // METHOD KHUSUS UNTUK UPLOAD FOTO DI DALAM MODAL
    public function uploadPhoto(): void
    {
        if (!auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        // Standar Bank: Hanya Administrator Pengelola yang berwenang mengunggah foto
        abort_if(!auth()->user()?->isAdmin(), 403, 'Akses Ditolak: Hanya Administrator Pengelola yang berwenang mengunggah foto dokumentasi fisik aset.');

        $this->validate([
            'newPhoto' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'photo_note' => 'nullable|string|max:255',
        ]);

        if ($this->selectedAsset && $this->newPhoto) {
            $path = $this->newPhoto->store('assets', 'public');

            AssetPhoto::create([
                'asset_id' => $this->selectedAsset->id,
                'photo_path' => $path,
                'condition_note' => $this->photo_note ?: 'Dokumentasi foto fisik (' . ucfirst(str_replace('_', ' ', $this->selectedAsset->status)) . ')',
                'uploaded_by' => auth()->id(),
            ]);

            Log::info('AUDIT INVENTORY PHOTO: Foto fisik diunggah pada aset.', [
                'asset_id' => $this->selectedAsset->id,
                'user_id' => auth()->id(),
            ]);

            // Refresh galeri foto di modal
            $this->selectedAsset->load('photos.uploader');
            $this->reset(['newPhoto', 'photo_note']);
            $this->success('Foto kondisi fisik berhasil diunggah!');
        }
    }

    // METHOD HAPUS FOTO
    public function deletePhoto(int $photoId): void
    {
        if (!auth()->check()) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        // Standar Bank: Hanya Administrator Pengelola yang berwenang menghapus foto
        abort_if(!auth()->user()?->isAdmin(), 403, 'Akses Ditolak: Hanya Administrator Pengelola yang berwenang menghapus foto dokumentasi fisik.');

        $photo = AssetPhoto::findOrFail($photoId);
        $assetId = $photo->asset_id;
        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        Log::warning('AUDIT INVENTORY PHOTO DELETE: Foto fisik dihapus dari aset.', [
            'photo_id' => $photoId,
            'asset_id' => $assetId,
            'user_id' => auth()->id(),
        ]);

        if ($this->selectedAsset) {
            $this->selectedAsset->load('photos.uploader');
        }
        $this->success('Foto berhasil dihapus.');
    }

    public function assets()
    {
        return Asset::query()
            ->with(['photos'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'ilike', "%{$this->search}%")
                        ->orWhere('code', 'ilike', "%{$this->search}%");
                });
            })
            ->when($this->filterRoom, fn($q) => $q->where('room_code', $this->filterRoom))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate(10);
    }

    public function with(): array
    {
        return [
            'assets' => $this->assets(),
            'headers' => $this->headers(),
            'rooms' => Room::all(),
            'editingAsset' => $this->editingAssetId ? Asset::with('photos')->find($this->editingAssetId) : null,
            'countTotal' => Asset::count(),
            'countBagus' => Asset::where('status', 'bagus')->count(),
            'countPerbaikan' => Asset::where('status', 'perlu_perbaikan')->count(),
            'countRusak' => Asset::where('status', 'rusak')->count(),
        ];
    }
}; ?>

<div class="pb-28 sm:pb-32">
    <x-toast />

    {{-- 1. Header & User Profile --}}
    @include('pages.inventory.partials.header')

    {{-- 2. Banner Otoritas Tata Kelola Peran (RBAC Standar Bank) --}}
    @include('pages.inventory.partials.role-banner')

    {{-- 3. Ringkasan Metrik Statistik --}}
    @include('pages.inventory.partials.stats')

    {{-- 4. Filter & Pencarian Cepat --}}
    @include('pages.inventory.partials.filters')

    {{-- 5. Tabel Data Inventaris --}}
    @include('pages.inventory.partials.table')

    {{-- 6. Modal Detail & Galeri Foto Lapangan --}}
    @include('pages.inventory.partials.modal-detail')

    {{-- 7. Modal Form Tambah / Ubah Data Aset --}}
    @include('pages.inventory.partials.modal-form')

    {{-- 8. Floating Action Button Tambah Aset --}}
    @include('pages.inventory.partials.floating-action')
</div>
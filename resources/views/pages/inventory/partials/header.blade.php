{{-- HEADER HALAMAN & PROFIL PENGGUNA TUNGGAL DI KANAN ATAS --}}
<x-header title="Pengelolaan & Inventaris Aset" subtitle="Monitoring fasilitas fisik mess cowok Tanjung Lesung (1 Lantai)" separator progress-indicator>
    <x-slot:middle class="!justify-end">
        @if($user = auth()->user())
            <x-user-badge :user="$user" />
        @else
            <a href="/login" class="btn btn-sm btn-outline btn-primary gap-2 shadow-xs">
                <x-icon name="o-arrow-right-on-rectangle" class="w-4 h-4" />
                <span>Login Pengelola</span>
            </a>
        @endif
    </x-slot:middle>

    @can('manage-assets')
        <x-slot:actions>
            <x-button label="Tambah Aset" icon="o-plus" wire:click="create" class="btn-primary btn-sm shadow-xs" responsive />
        </x-slot:actions>
    @endcan
</x-header>

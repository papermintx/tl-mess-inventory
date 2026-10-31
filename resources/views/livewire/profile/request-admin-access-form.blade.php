<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $reason = '';
    public bool $showSuccess = false;

    public function requestAccess(): void
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        $this->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'admin_request_status' => 'pending',
            'admin_request_reason' => $this->reason ?: 'Permohonan diajukan melalui halaman profil.',
            'admin_requested_at' => now(),
        ]);

        $this->reason = '';
        $this->showSuccess = true;
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Status Peran & Hak Akses
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Informasi peran Anda dalam sistem inventaris mess dan pengajuan hak akses Administrator.
        </p>
    </header>

    <div class="mt-4 p-4 rounded-xl border {{ auth()->user()->isFirstAdmin() ? 'bg-amber-50/60 border-amber-200' : (auth()->user()->isAdmin() ? 'bg-blue-50/60 border-blue-200' : 'bg-gray-50 border-gray-200') }}">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">
                    @if(auth()->user()->isFirstAdmin())
                        👑
                    @elseif(auth()->user()->isAdmin())
                        🛡️
                    @else
                        👁️
                    @endif
                </span>
                <div>
                    <span class="font-bold text-sm text-gray-900">
                        @if(auth()->user()->isFirstAdmin())
                            Administrator Pertama (Super Admin)
                        @elseif(auth()->user()->isAdmin())
                            Administrator Pengelola
                        @else
                            Staff Peninjau (Viewer)
                        @endif
                    </span>
                    <p class="text-xs text-gray-500">
                        @if(auth()->user()->isFirstAdmin())
                            Pemegang otoritas tunggal untuk menyetujui hak akses admin bagi pengguna lain.
                        @elseif(auth()->user()->isAdmin())
                            Memiliki wewenang penuh mengelola inventaris, aset, dan dokumentasi fisik.
                        @else
                            Akses baca (read-only) untuk memantau data inventaris dan galeri aset.
                        @endif
                    </p>
                </div>
            </div>

            @if(auth()->user()->isFirstAdmin())
                <a href="{{ route('admin.access-requests') }}" class="inline-flex items-center px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm transition" wire:navigate>
                    Kelola Persetujuan Admin
                </a>
            @endif
        </div>
    </div>

    {{-- Logika untuk Pengguna Viewer yang belum Admin --}}
    @if(! auth()->user()->isAdmin())
        <div class="mt-6">
            @if(auth()->user()->admin_request_status === 'pending')
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 flex items-start gap-3">
                    <span class="text-base">⏳</span>
                    <div>
                        <p class="font-semibold text-amber-900">Permohonan Hak Akses Admin Sedang Ditinjau</p>
                        <p class="text-amber-800/80 mt-1 leading-relaxed">
                            Permohonan Anda diajukan pada <b>{{ auth()->user()->admin_requested_at?->format('d M Y, H:i') }} WIB</b>. Hanya <b>Administrator Pertama</b> yang dapat menyetujui atau menolak permohonan ini.
                        </p>
                        @if(auth()->user()->admin_request_reason)
                            <p class="mt-2 text-xs text-amber-700 italic bg-amber-100/60 p-2 rounded-lg">
                                "Catatan Anda: {{ auth()->user()->admin_request_reason }}"
                            </p>
                        @endif
                    </div>
                </div>
            @else
                @if(auth()->user()->admin_request_status === 'rejected')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800">
                        Permohonan sebelumnya ditolak oleh Administrator Pertama. Anda dapat mengajukan permohonan kembali dengan menyertakan alasan yang jelas.
                    </div>
                @endif

                @if($showSuccess)
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-xs text-green-800">
                        Permohonan Anda berhasil dikirim ke Administrator Pertama!
                    </div>
                @endif

                <form wire:submit="requestAccess" class="space-y-4">
                    <div>
                        <x-input-label for="reason" value="Alasan / Jabatan Pengajuan Akses Administrator" />
                        <textarea 
                            wire:model="reason" 
                            id="reason"
                            class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs" 
                            placeholder="Jelaskan kebutuhan Anda (misal: bertugas sebagai staf logistik mess)..."
                            rows="2"
                        ></textarea>
                        <x-input-error :messages="$errors->get('reason')" class="mt-1" />
                    </div>

                    <x-primary-button>
                        Ajukan Menjadi Administrator
                    </x-primary-button>
                </form>
            @endif
        </div>
    @endif
</section>

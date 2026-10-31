<?php

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new #[Title('Kelola Hak Akses Administrator - Mess Tanjung Lesung')] class extends Component
{
    use Toast, WithPagination;

    public string $search = '';
    public string $activeTab = 'pending'; // 'pending' | 'all'

    public function mount(): void
    {
        if (! auth()->user()?->isFirstAdmin()) {
            abort(403, 'Akses Ditolak: Hanya Administrator Pertama yang memiliki kewenangan menyetujui hak akses admin.');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
    }

    public function approve(int $userId): void
    {
        if (! auth()->user()?->isFirstAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($userId);

        if ($user->isFirstAdmin()) {
            return;
        }

        $user->update([
            'role' => 'admin',
            'admin_request_status' => 'approved',
            'admin_action_at' => now(),
            'admin_action_by' => auth()->id(),
        ]);

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles(['admin']);
        }

        $this->success("Pengguna {$user->name} berhasil disetujui sebagai Administrator!", position: 'toast-top');
    }

    public function reject(int $userId): void
    {
        if (! auth()->user()?->isFirstAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($userId);

        if ($user->isFirstAdmin()) {
            return;
        }

        $user->update([
            'admin_request_status' => 'rejected',
            'admin_action_at' => now(),
            'admin_action_by' => auth()->id(),
        ]);

        $this->info("Permohonan akses admin untuk {$user->name} telah ditolak.", position: 'toast-top');
    }

    public function revokeAdmin(int $userId): void
    {
        if (! auth()->user()?->isFirstAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($userId);

        if ($user->isFirstAdmin()) {
            $this->error('Hak akses Administrator Pertama tidak dapat dicabut.', position: 'toast-top');
            return;
        }

        $user->update([
            'role' => 'viewer',
            'admin_request_status' => null,
            'admin_action_at' => now(),
            'admin_action_by' => auth()->id(),
        ]);

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles(['viewer']);
        }

        $this->warning("Hak akses Admin untuk {$user->name} berhasil dicabut dan dikembalikan ke Viewer.", position: 'toast-top');
    }

    public function promoteToAdmin(int $userId): void
    {
        if (! auth()->user()?->isFirstAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($userId);

        $user->update([
            'role' => 'admin',
            'admin_request_status' => 'approved',
            'admin_action_at' => now(),
            'admin_action_by' => auth()->id(),
        ]);

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles(['admin']);
        }

        $this->success("{$user->name} berhasil dipromosikan menjadi Administrator!", position: 'toast-top');
    }

    public function with(): array
    {
        $pendingQuery = User::where('admin_request_status', 'pending');
        if (! empty($this->search)) {
            $pendingQuery->where(function ($q) {
                $q->where('name', 'ilike', "%{$this->search}%")
                  ->orWhere('email', 'ilike', "%{$this->search}%")
                  ->orWhere('username', 'ilike', "%{$this->search}%");
            });
        }
        $pendingUsers = $pendingQuery->latest('admin_requested_at')->paginate(10, ['*'], 'pending_page');

        $allUsersQuery = User::query();
        if (! empty($this->search)) {
            $allUsersQuery->where(function ($q) {
                $q->where('name', 'ilike', "%{$this->search}%")
                  ->orWhere('email', 'ilike', "%{$this->search}%")
                  ->orWhere('username', 'ilike', "%{$this->search}%");
            });
        }
        $allUsers = $allUsersQuery->orderByDesc('is_first_admin')
            ->orderBy('name')
            ->paginate(15, ['*'], 'all_page');

        return [
            'pendingUsers' => $pendingUsers,
            'allUsers' => $allUsers,
            'countPending' => User::where('admin_request_status', 'pending')->count(),
            'countTotal' => User::count(),
            'countAdmins' => User::where('role', 'admin')->count(),
            'countViewers' => User::where('role', 'viewer')->count(),
        ];
    }
}; ?>

<div class="space-y-6 pb-20">
    <x-toast />

    {{-- Header Halaman Otoritas --}}
    <div class="bg-base-100 rounded-2xl p-6 shadow-sm border border-base-300/80">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/15 text-amber-600 flex items-center justify-center font-bold text-2xl shadow-inner shrink-0">
                    👑
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-base-content">
                            Pusat Persetujuan Administrator
                        </h1>
                        <span class="badge badge-warning badge-sm font-semibold text-xs">Otoritas Tunggal</span>
                    </div>
                    <p class="text-xs sm:text-sm text-base-content/70 mt-1">
                        Hanya Anda (<b>Administrator Pertama</b>) yang memiliki hak eksklusif untuk memberikan atau mencabut akses Administrator bagi pengguna lain.
                    </p>
                </div>
            </div>

            <a href="{{ route('inventory.index') }}" class="btn btn-ghost btn-sm text-xs gap-1" wire:navigate>
                <x-icon name="o-arrow-left" class="w-4 h-4" />
                <span>Kembali ke Inventaris</span>
            </a>
        </div>
    </div>

    {{-- Ringkasan Statistik Pengguna & Permohonan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-base-100 p-4 rounded-xl border border-base-300 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-warning/15 text-warning flex items-center justify-center text-lg">
                ⏳
            </div>
            <div>
                <span class="text-xs text-base-content/60 font-medium block">Menunggu Persetujuan</span>
                <span class="text-xl font-bold text-base-content">{{ $countPending }}</span>
            </div>
        </div>

        <div class="bg-base-100 p-4 rounded-xl border border-base-300 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary/15 text-primary flex items-center justify-center text-lg">
                🛡️
            </div>
            <div>
                <span class="text-xs text-base-content/60 font-medium block">Administrator Aktif</span>
                <span class="text-xl font-bold text-base-content">{{ $countAdmins }}</span>
            </div>
        </div>

        <div class="bg-base-100 p-4 rounded-xl border border-base-300 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-neutral/15 text-neutral-content flex items-center justify-center text-lg">
                👁️
            </div>
            <div>
                <span class="text-xs text-base-content/60 font-medium block">Staff Viewer (Read-only)</span>
                <span class="text-xl font-bold text-base-content">{{ $countViewers }}</span>
            </div>
        </div>

        <div class="bg-base-100 p-4 rounded-xl border border-base-300 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-info/15 text-info flex items-center justify-center text-lg">
                👥
            </div>
            <div>
                <span class="text-xs text-base-content/60 font-medium block">Total Seluruh Akun</span>
                <span class="text-xl font-bold text-base-content">{{ $countTotal }}</span>
            </div>
        </div>
    </div>

    {{-- Navigasi Tab & Pencarian --}}
    <div class="bg-base-100 rounded-2xl shadow-sm border border-base-300 overflow-hidden">
        <div class="p-4 border-b border-base-300 flex flex-col sm:flex-row items-center justify-between gap-4">
            {{-- Tabs --}}
            <div class="join w-full sm:w-auto">
                <button 
                    wire:click="$set('activeTab', 'pending')" 
                    class="join-item btn btn-sm {{ $activeTab === 'pending' ? 'btn-primary' : 'btn-ghost' }} gap-2 flex-1 sm:flex-initial"
                >
                    <x-icon name="o-inbox-stack" class="w-4 h-4" />
                    <span>Permohonan Menunggu</span>
                    @if($countPending > 0)
                        <span class="badge badge-warning badge-xs font-bold">{{ $countPending }}</span>
                    @endif
                </button>
                <button 
                    wire:click="$set('activeTab', 'all')" 
                    class="join-item btn btn-sm {{ $activeTab === 'all' ? 'btn-primary' : 'btn-ghost' }} gap-2 flex-1 sm:flex-initial"
                >
                    <x-icon name="o-users" class="w-4 h-4" />
                    <span>Semua Pengguna</span>
                </button>
            </div>

            {{-- Pencarian --}}
            <div class="w-full sm:w-72">
                <x-input 
                    wire:model.live.debounce.300ms="search" 
                    icon="o-magnifying-glass" 
                    placeholder="Cari nama, username, email..." 
                    class="input-sm w-full"
                />
            </div>
        </div>

        {{-- TAB 1: PERMOHONAN MENUNGGU (PENDING) --}}
        @if ($activeTab === 'pending')
            <div class="p-4">
                @if ($pendingUsers->isEmpty())
                    <div class="text-center py-12 px-4">
                        <div class="w-16 h-16 rounded-full bg-success/10 text-success flex items-center justify-center mx-auto text-2xl mb-3">
                            ✓
                        </div>
                        <h3 class="font-bold text-base text-base-content">Tidak Ada Permohonan Tertunda</h3>
                        <p class="text-xs text-base-content/60 mt-1 max-w-sm mx-auto">
                            Semua permohonan hak akses administrator telah ditinjau atau belum ada pengguna yang mengajukan.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full text-xs">
                            <thead>
                                <tr class="text-base-content/70">
                                    <th>PENGGUNA</th>
                                    <th>USERNAME / EMAIL</th>
                                    <th>WAKTU PENGAJUAN</th>
                                    <th>ALASAN PENGAJUAN</th>
                                    <th class="text-center">AKSI PERSETUJUAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingUsers as $user)
                                    <tr class="hover">
                                        <td class="font-semibold text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <span class="block font-bold text-base-content">{{ $user->name }}</span>
                                                    <span class="badge badge-neutral badge-xs">Saat ini: Viewer</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-xs">
                                                <span class="font-medium text-base-content block">{{ $user->username ?? '-' }}</span>
                                                <span class="text-base-content/60 block">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap text-base-content/70">
                                            {{ $user->admin_requested_at?->format('d M Y, H:i') ?? '-' }} WIB
                                        </td>
                                        <td class="max-w-xs">
                                            <div class="p-2 bg-base-200 rounded-lg text-xs italic text-base-content/80">
                                                "{{ $user->admin_request_reason ?? 'Tidak ada catatan tambahan.' }}"
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center gap-2">
                                                <button 
                                                    wire:click="approve({{ $user->id }})" 
                                                    wire:confirm="Yakin ingin menyetujui {{ $user->name }} menjadi Administrator Pengelola?" 
                                                    class="btn btn-success btn-xs text-white gap-1 shadow-sm"
                                                >
                                                    <x-icon name="o-check" class="w-3.5 h-3.5" />
                                                    <span>Setujui Jadi Admin</span>
                                                </button>

                                                <button 
                                                    wire:click="reject({{ $user->id }})" 
                                                    wire:confirm="Yakin ingin menolak permohonan akses admin untuk {{ $user->name }}?" 
                                                    class="btn btn-error btn-xs text-white gap-1 shadow-sm"
                                                >
                                                    <x-icon name="o-x-mark" class="w-3.5 h-3.5" />
                                                    <span>Tolak</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pendingUsers->links() }}
                    </div>
                @endif
            </div>
        @endif

        {{-- TAB 2: SEMUA PENGGUNA & STATUS ROLE --}}
        @if ($activeTab === 'all')
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full text-xs">
                        <thead>
                            <tr class="text-base-content/70">
                                <th>NAMA PENGGUNA</th>
                                <th>USERNAME / EMAIL</th>
                                <th>STATUS PERAN (ROLE)</th>
                                <th>STATUS PERMOHONAN</th>
                                <th class="text-center">KELOLA AKSES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allUsers as $user)
                                <tr class="hover">
                                    <td class="font-semibold text-sm">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full {{ $user->isFirstAdmin() ? 'bg-amber-500/20 text-amber-700' : ($user->isAdmin() ? 'bg-primary/10 text-primary' : 'bg-base-300 text-base-content') }} flex items-center justify-center font-bold text-xs uppercase">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="block font-bold text-base-content">{{ $user->name }}</span>
                                                    @if($user->isFirstAdmin())
                                                        <span class="badge badge-warning badge-xs font-bold text-[10px]">👑 Utama</span>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-base-content/60">Terdaftar: {{ $user->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs">
                                            <span class="font-medium text-base-content block">{{ $user->username ?? '-' }}</span>
                                            <span class="text-base-content/60 block">{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->isFirstAdmin())
                                            <span class="badge badge-warning badge-sm font-bold">Administrator Pertama</span>
                                        @elseif($user->isAdmin())
                                            <span class="badge badge-primary badge-sm font-semibold">Administrator Pengelola</span>
                                        @else
                                            <span class="badge badge-neutral badge-sm">Staff Peninjau (Viewer)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->isFirstAdmin())
                                            <span class="text-xs text-amber-600 font-semibold">Pemilik Otoritas</span>
                                        @elseif($user->admin_request_status === 'pending')
                                            <span class="badge badge-warning badge-xs font-medium">⏳ Menunggu Review</span>
                                        @elseif($user->admin_request_status === 'approved')
                                            <span class="badge badge-success badge-xs font-medium text-white">✓ Disetujui</span>
                                        @elseif($user->admin_request_status === 'rejected')
                                            <span class="badge badge-error badge-xs font-medium text-white">✕ Ditolak</span>
                                        @else
                                            <span class="text-xs text-base-content/40">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->isFirstAdmin())
                                            <span class="text-xs text-base-content/50 italic">Terkunci (Akun Utama)</span>
                                        @elseif($user->isAdmin())
                                            <button 
                                                wire:click="revokeAdmin({{ $user->id }})" 
                                                wire:confirm="Yakin ingin mencabut hak Administrator dari {{ $user->name }} dan menurunkannya menjadi Viewer?"
                                                class="btn btn-outline btn-error btn-xs gap-1"
                                            >
                                                <x-icon name="o-arrow-down" class="w-3.5 h-3.5" />
                                                <span>Cabut Hak Admin</span>
                                            </button>
                                        @else
                                            <button 
                                                wire:click="promoteToAdmin({{ $user->id }})" 
                                                wire:confirm="Yakin ingin langsung menjadikan {{ $user->name }} sebagai Administrator?"
                                                class="btn btn-outline btn-primary btn-xs gap-1"
                                            >
                                                <x-icon name="o-shield-check" class="w-3.5 h-3.5" />
                                                <span>Jadikan Admin</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $allUsers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

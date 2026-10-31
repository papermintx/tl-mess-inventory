<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $request_admin = false;
    public string $admin_request_reason = '';

    public bool $isFirstUser = false;

    public function mount(): void
    {
        // Deteksi apakah sistem belum memiliki Administrator Utama
        $this->isFirstUser = ! User::where('is_first_admin', true)->exists()
            && User::where('role', 'admin')->count() === 0;
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ];

        if (! empty($this->username)) {
            $rules['username'] = ['string', 'alpha_dash', 'max:50', 'unique:'.User::class.',username'];
        }

        if ($this->request_admin && ! $this->isFirstUser) {
            $rules['admin_request_reason'] = ['nullable', 'string', 'max:500'];
        }

        $validated = $this->validate($rules);

        $validated['username'] = ! empty($this->username) ? $this->username : explode('@', $this->email)[0];
        $validated['password'] = Hash::make($validated['password']);

        // Pastikan role Spatie sudah tersedia
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        if ($this->isFirstUser) {
            // Pendaftar pertama otomatis menjadi Administrator Utama
            $validated['role'] = 'admin';
            $validated['is_first_admin'] = true;
            $validated['admin_request_status'] = 'approved';
            $validated['admin_action_at'] = now();

            $user = User::create($validated);
            $user->assignRole('admin');
        } else {
            // Pengguna selanjutnya default sebagai Viewer
            $validated['role'] = 'viewer';
            $validated['is_first_admin'] = false;

            if ($this->request_admin) {
                $validated['admin_request_status'] = 'pending';
                $validated['admin_request_reason'] = ! empty($this->admin_request_reason) 
                    ? $this->admin_request_reason 
                    : 'Permohonan diajukan saat pendaftaran akun baru.';
                $validated['admin_requested_at'] = now();
            } else {
                $validated['admin_request_status'] = null;
            }

            $user = User::create($validated);
            $user->assignRole('viewer');
        }

        event(new Registered($user));

        Auth::login($user);

        if ($this->isFirstUser) {
            session()->flash('status', 'Selamat! Anda terdaftar sebagai Administrator Pertama.');
        } elseif ($this->request_admin) {
            session()->flash('status', 'Pendaftaran berhasil. Permohonan akses Administrator Anda sedang menunggu persetujuan dari Administrator Pertama.');
        }

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-5 text-center">
        <h2 class="text-xl font-bold text-gray-800">Daftar Akun Baru</h2>
        <p class="text-xs text-gray-500 mt-1">Sistem Inventaris Mess Kawasan Tanjung Lesung</p>
    </div>

    {{-- Banner Khusus Pendaftar Pertama --}}
    @if ($isFirstUser)
        <div class="mb-5 p-3.5 bg-amber-500/10 border border-amber-500/30 rounded-xl text-xs text-amber-900 flex items-start gap-2.5">
            <span class="text-lg">👑</span>
            <div>
                <p class="font-bold text-amber-900">Pendaftaran Administrator Pertama</p>
                <p class="text-amber-800/90 mt-0.5 leading-relaxed">
                    Sistem mendeteksi belum ada Administrator. Akun pertama yang didaftarkan ini akan otomatis menjadi <b>Administrator Utama (Pemilik Otoritas Tunggal)</b> yang berwenang menyetujui hak akses admin bagi staf lain.
                </p>
            </div>
        </div>
    @endif

    <form wire:submit="register">
        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" value="Username (Opsional)" />
            <x-text-input wire:model="username" id="username" class="block mt-1 w-full" type="text" name="username" autocomplete="username" placeholder="johndoe" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Alamat Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="email" placeholder="john@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Opsi Pengajuan Akses Admin (Hanya jika bukan pendaftar pertama) --}}
        @if (! $isFirstUser)
            <div class="mt-5 p-3.5 bg-gray-50 rounded-xl border border-gray-200 space-y-2">
                <label class="flex items-start gap-2.5 cursor-pointer select-none">
                    <input 
                        type="checkbox" 
                        wire:model.live="request_admin" 
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mt-0.5"
                    />
                    <div class="text-xs">
                        <span class="font-semibold text-gray-800 block">Ajukan Hak Akses Administrator Pengelola</span>
                        <span class="text-gray-500 block leading-relaxed">
                            Centang jika Anda bertugas mengelola inventaris. Permohonan Anda akan ditinjau oleh Administrator Pertama.
                        </span>
                    </div>
                </label>

                @if ($request_admin)
                    <div class="pt-2">
                        <x-input-label for="admin_request_reason" value="Alasan / Jabatan Tugas (Opsional)" />
                        <textarea 
                            wire:model="admin_request_reason" 
                            id="admin_request_reason"
                            class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs" 
                            placeholder="Contoh: Staf Logistik & Inventaris Mess Kawasan..."
                            rows="2"
                        ></textarea>
                        <x-input-error :messages="$errors->get('admin_request_reason')" class="mt-1" />
                    </div>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                Sudah punya akun? Masuk
            </a>

            <x-primary-button class="ms-4">
                {{ $isFirstUser ? 'Daftar Sebagai Admin Utama' : 'Daftar Sekarang' }}
            </x-primary-button>
        </div>
    </form>
</div>

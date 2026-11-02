<?php

use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new #[Layout('layouts.guest')] class extends Component
{
    public int $step = 1; // 1: Form Data, 2: Verifikasi OTP

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $admin_request_reason = '';
    public string $otp_code = '';

    public bool $isFirstUser = false;

    public function mount(): void
    {
        // Deteksi apakah sistem belum memiliki Administrator Utama
        $this->isFirstUser = ! User::where('is_first_admin', true)->exists()
            && User::where('role', 'admin')->count() === 0;
    }

    /**
     * Langkah 1: Validasi form & kirim kode OTP ke email.
     */
    public function sendOtp(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ];

        if (! empty($this->username)) {
            $rules['username'] = ['string', 'regex:/^[a-zA-Z0-9._-]+$/', 'max:50', 'unique:'.User::class.',username'];
        }

        if (! $this->isFirstUser) {
            $rules['admin_request_reason'] = ['nullable', 'string', 'max:500'];
        }

        $this->validate($rules);

        // Generate 6 digit angka OTP
        $otp = (string) random_int(100000, 999999);

        // Hapus kode OTP lama untuk email ini jika ada
        EmailOtp::where('email', $this->email)->delete();

        // Simpan OTP dan data sementara ke tabel email_otps (berlaku 15 menit)
        EmailOtp::create([
            'email' => $this->email,
            'otp' => $otp,
            'payload' => [
                'name' => $this->name,
                'username' => ! empty($this->username) ? $this->username : explode('@', $this->email)[0],
                'password' => Hash::make($this->password),
                'admin_request_reason' => $this->admin_request_reason,
            ],
            'expires_at' => now()->addMinutes(15),
        ]);

        // Kirim email OTP
        try {
            Mail::to($this->email)->send(new SendOtpMail($otp, $this->name));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email OTP: '.$e->getMessage());
        }

        $this->step = 2;
        $this->resetErrorBag();
        session()->flash('status', 'Kode OTP 6-digit telah dikirimkan ke email Anda.');
    }

    /**
     * Kirim ulang kode OTP jika belum menerima.
     */
    public function resendOtp(): void
    {
        $otp = (string) random_int(100000, 999999);

        EmailOtp::where('email', $this->email)->delete();

        EmailOtp::create([
            'email' => $this->email,
            'otp' => $otp,
            'payload' => [
                'name' => $this->name,
                'username' => ! empty($this->username) ? $this->username : explode('@', $this->email)[0],
                'password' => Hash::make($this->password),
                'admin_request_reason' => $this->admin_request_reason,
            ],
            'expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::to($this->email)->send(new SendOtpMail($otp, $this->name));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang email OTP: '.$e->getMessage());
        }

        session()->flash('status', 'Kode OTP baru telah berhasil dikirim ke '.$this->email);
    }

    /**
     * Langkah 2: Verifikasi kode OTP dan selesaikan pembuatan akun.
     */
    public function verifyOtpAndRegister(): void
    {
        $this->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ], [
            'otp_code.required' => 'Masukkan 6 digit kode OTP yang diterima.',
            'otp_code.size' => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $otpRecord = EmailOtp::where('email', $this->email)->latest()->first();

        if (! $otpRecord || ! $otpRecord->isValid($this->otp_code)) {
            $this->addError('otp_code', 'Kode OTP salah atau sudah kedaluwarsa. Silakan periksa kembali atau kirim ulang kode.');
            return;
        }

        $payload = $otpRecord->payload;

        // Pastikan role Spatie sudah tersedia
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        $userData = [
            'name' => $payload['name'] ?? $this->name,
            'username' => $payload['username'] ?? (! empty($this->username) ? $this->username : explode('@', $this->email)[0]),
            'email' => $this->email,
            'password' => $payload['password'] ?? Hash::make($this->password),
            'email_verified_at' => now(),
        ];

        if ($this->isFirstUser) {
            // Pendaftar pertama otomatis menjadi Administrator Utama
            $userData['role'] = 'admin';
            $userData['is_first_admin'] = true;
            $userData['admin_request_status'] = 'approved';
            $userData['admin_action_at'] = now();

            $user = User::create($userData);
            $user->assignRole('admin');
        } else {
            // Pengguna selanjutnya default sebagai Viewer dengan status permohonan pending
            $userData['role'] = 'viewer';
            $userData['is_first_admin'] = false;
            $userData['admin_request_status'] = 'pending';
            $userData['admin_request_reason'] = ! empty($payload['admin_request_reason'])
                ? $payload['admin_request_reason']
                : 'Permohonan diajukan melalui pendaftaran pengelola mess.';
            $userData['admin_requested_at'] = now();

            $user = User::create($userData);
            $user->assignRole('viewer');
        }

        // Hapus kode OTP yang telah sukses diverifikasi
        $otpRecord->delete();

        event(new Registered($user));

        Auth::login($user);

        if ($this->isFirstUser) {
            session()->flash('status', 'Selamat! Email terverifikasi dan akun Anda terdaftar sebagai Administrator Utama.');
        } else {
            session()->flash('status', 'Email terverifikasi! Permohonan hak akses Administrator Anda telah dikirim dan menunggu persetujuan Administrator Pertama.');
        }

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- ======================================================== --}}
    {{-- LANGKAH 1: FORM PENDAFTARAN & PENGAJUAN ADMIN --}}
    {{-- ======================================================== --}}
    @if ($step === 1)
        <div class="mb-5 text-center">
            <h2 class="text-xl font-bold text-gray-800">Pendaftaran Pengelola Mess</h2>
            <p class="text-xs text-gray-500 mt-1">Kawasan Wisata Tanjung Lesung</p>
        </div>

        {{-- Banner Khusus Pendaftar Pertama --}}
        @if ($isFirstUser)
            <div class="mb-5 p-3.5 bg-amber-500/10 border border-amber-500/30 rounded-xl text-xs text-amber-900 flex items-start gap-2.5">
                <span class="text-lg">👑</span>
                <div>
                    <p class="font-bold text-amber-900">Pendaftaran Administrator Pertama</p>
                    <p class="text-amber-800/90 mt-0.5 leading-relaxed">
                        Belum ada Administrator di sistem. Akun pertama ini akan otomatis menjadi <b>Administrator Utama</b> yang memegang hak persetujuan admin bagi staf lain.
                    </p>
                </div>
            </div>
        @else
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-start gap-2">
                <span class="text-base">ℹ️</span>
                <p class="leading-relaxed">
                    Pengunjung publik dapat langsung melihat inventaris tanpa akun. Formulir ini khusus untuk staf yang mengajukan <b>Hak Akses Administrator Pengelola</b>.
                </p>
            </div>
        @endif

        <form wire:submit="sendOtp">
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
                <x-input-label for="email" value="Alamat Email (Untuk Menerima OTP)" />
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

            {{-- Alasan Pengajuan (Jika bukan pendaftar pertama) --}}
            @if (! $isFirstUser)
                <div class="mt-4">
                    <x-input-label for="admin_request_reason" value="Alasan / Jabatan Pengajuan Admin" />
                    <textarea 
                        wire:model="admin_request_reason" 
                        id="admin_request_reason"
                        class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs" 
                        placeholder="Contoh: Petugas logistik & inventaris mess kawasan..."
                        rows="2"
                    ></textarea>
                    <x-input-error :messages="$errors->get('admin_request_reason')" class="mt-1" />
                </div>
            @endif

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                    Sudah punya akun? Masuk
                </a>

                <x-primary-button class="ms-4">
                    Kirim Kode OTP Verifikasi &rarr;
                </x-primary-button>
            </div>
        </form>
    @endif

    {{-- ======================================================== --}}
    {{-- LANGKAH 2: VERIFIKASI KODE OTP DARI EMAIL --}}
    {{-- ======================================================== --}}
    @if ($step === 2)
        <div class="text-center space-y-2 mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 text-xl font-bold">
                ✉️
            </div>
            <h2 class="text-lg font-bold text-gray-800">Verifikasi Email Anda</h2>
            <p class="text-xs text-gray-600 max-w-sm mx-auto leading-relaxed">
                Kami telah mengirimkan 6 digit kode OTP ke alamat: <br>
                <b class="text-gray-900 font-semibold">{{ $email }}</b>
            </p>
        </div>

        <form wire:submit="verifyOtpAndRegister" class="space-y-4">
            <div>
                <x-input-label for="otp_code" value="Masukkan 6 Digit Kode OTP" class="text-center text-xs" />
                <x-text-input 
                    wire:model="otp_code" 
                    id="otp_code" 
                    class="block mt-2 w-full text-center text-2xl font-mono tracking-widest font-bold py-3" 
                    type="text" 
                    maxlength="6" 
                    placeholder="000000" 
                    autofocus 
                    required 
                />
                <x-input-error :messages="$errors->get('otp_code')" class="mt-2 text-center" />
            </div>

            <div class="pt-2">
                <x-primary-button class="w-full justify-center py-2.5">
                    Verifikasi & Ajukan Akun &rarr;
                </x-primary-button>
            </div>

            <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                <button type="button" wire:click="resendOtp" class="text-indigo-600 hover:underline font-medium">
                    Kirim Ulang Kode OTP
                </button>

                <button type="button" wire:click="$set('step', 1)" class="text-gray-600 hover:underline">
                    &larr; Ubah Email / Data
                </button>
            </div>
        </form>
    @endif
</div>

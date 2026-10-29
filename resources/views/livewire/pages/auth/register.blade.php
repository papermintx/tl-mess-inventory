<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

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

        $validated = $this->validate($rules);

        $validated['username'] = ! empty($this->username) ? $this->username : explode('@', $this->email)[0];
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'viewer';

        $user = User::create($validated);

        // Berikan role Spatie default 'viewer'
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('viewer');
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800">Daftar Akun Baru</h2>
        <p class="text-xs text-gray-500 mt-1">Sistem Inventaris Mess Tanjung Lesung</p>
    </div>

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

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                Sudah punya akun? Masuk
            </a>

            <x-primary-button class="ms-4">
                Daftar Sekarang
            </x-primary-button>
        </div>
    </form>
</div>

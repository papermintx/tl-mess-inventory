<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('inventory.index', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800">Masuk ke Sistem</h2>
        <p class="text-xs text-gray-500 mt-1">Sistem Inventaris Mess Tanjung Lesung</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Username / Email Address -->
        <div>
            <x-input-label for="email" value="Username atau Email" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="text" name="email" required autofocus autocomplete="username" placeholder="Masukkan username atau email" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="flex flex-col gap-1">
                <a class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}" wire:navigate>
                    Belum punya akun? Daftar
                </a>
                @if (Route::has('password.request'))
                    <a class="underline text-xs text-gray-400 hover:text-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <x-primary-button class="ms-3">
                Masuk
            </x-primary-button>
        </div>
    </form>
</div>

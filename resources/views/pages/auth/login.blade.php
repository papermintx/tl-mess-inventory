<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

new #[Layout('layouts.auth')] class extends Component
{
    use Toast;

    #[Rule('required|string')]
    public string $username = '';

    #[Rule('required')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->success('Selamat datang kembali!', position: 'toast-top');

            return $this->redirectIntended('/inventory', navigate: true);
        }

        $this->error('Username atau kata sandi tidak sesuai.', position: 'toast-top');
    }
};  ?>

<div class="min-h-screen flex items-center justify-center bg-base-200 px-4 py-12">
    <div class="w-full max-w-md space-y-6">
        
        {{-- Brand / Header --}}
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary text-primary-content shadow-lg shadow-primary/30">
                <x-icon name="o-building-office-2" class="w-8 h-8" />
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-base-content">Sistem Inventaris Mess</h1>
            <p class="text-sm text-base-content/70">Kawasan Tanjung Lesung • Portofolio IT Bank Banten</p>
        </div>

        {{-- Login Card --}}
        <x-card class="shadow-xl bg-base-100 border border-base-content/5">
            <x-form wire:submit="login">
                <x-input 
                    label="Username" 
                    wire:model="username" 
                    icon="o-user" 
                    placeholder="Masukkan username" 
                    inline 
                />
                <x-input 
                    label="Kata Sandi" 
                    wire:model="password" 
                    type="password" 
                    icon="o-key" 
                    placeholder="••••••••" 
                    inline 
                />
                <div class="flex items-center justify-between text-sm py-1">
                    <x-checkbox label="Ingat saya" wire:model="remember" class="checkbox-primary checkbox-sm" />
                    <a href="/" class="text-primary hover:underline text-xs" wire:navigate>
                        Kembali ke Showcase
                    </a>
                </div>
                <x-slot:actions>
                    <x-button 
                        label="Masuk ke Sistem" 
                        type="submit" 
                        icon="o-arrow-right-on-rectangle" 
                        class="btn-primary w-full shadow-md shadow-primary/20" 
                        spinner="login" 
                    />
                </x-slot:actions>
            </x-form>
        </x-card>

        <p class="text-center text-xs text-base-content/50">
            Khusus petugas administrator untuk pengelolaan dan pembaruan data inventaris.
        </p>
    </div>
</div>
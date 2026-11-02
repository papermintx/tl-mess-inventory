<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public bool $modalDelete = false;
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setelah akun Anda dihapus, seluruh data dan hak akses Anda akan dihapus secara permanen. Pastikan Anda telah menyimpan informasi penting sebelum melanjutkan.') }}
        </p>
    </header>

    <x-button
        label="Hapus Akun"
        class="btn-error text-white"
        wire:click="$set('modalDelete', true)"
    />

    <x-modal wire:model="modalDelete" title="Konfirmasi Hapus Akun" subtitle="Tindakan ini permanen dan tidak dapat dibatalkan">
        <form wire:submit.prevent="deleteUser" class="space-y-4">
            <p class="text-sm text-base-content/80">
                Apakah Anda yakin ingin menghapus akun ini secara permanen? Masukkan kata sandi akun Anda untuk mengonfirmasi.
            </p>

            <x-input
                type="password"
                wire:model="password"
                label="Kata Sandi"
                placeholder="Masukkan kata sandi Anda"
                icon="o-key"
            />

            <div class="modal-action flex justify-end gap-2 pt-2">
                <x-button label="Batal" type="button" @click="$wire.modalDelete = false" />
                <x-button label="Hapus Akun Saya" class="btn-error text-white" type="submit" />
            </div>
        </form>
    </x-modal>
</section>

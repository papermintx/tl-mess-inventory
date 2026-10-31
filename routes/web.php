<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShowcaseController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Halaman Publik (Showcase - Bisa diakses Guest / Viewer / Admin)
Route::get('/', [ShowcaseController::class, 'index'])->name('showcase');

// Halaman Inventaris Mess (Bisa diakses Guest / Viewer / Admin)
Volt::route('/inventory', 'inventory.index')->name('inventory.index');

// Redirect /dashboard bawaan Breeze langsung ke /inventory
Route::get('/dashboard', function () {
    return redirect()->route('inventory.index');
})->middleware(['auth'])->name('dashboard');

// Profil Pengguna (Breeze)
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Halaman Khusus Administrator Pertama: Kelola Persetujuan Hak Akses Admin
Volt::route('/admin/access-requests', 'admin.access-requests')
    ->middleware(['auth'])
    ->name('admin.access-requests');

// Logout Pengguna
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Route Auth Breeze (login, register, forgot-password, logout)
require __DIR__.'/auth.php';

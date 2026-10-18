<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShowcaseController;
use Illuminate\Support\Facades\Route;

// Halaman Publik (Showcase & Inventaris - Bisa diakses oleh Guest / Viewer / Admin)
Route::get('/', [ShowcaseController::class, 'index'])->name('showcase');
Route::livewire('/inventory', 'pages::inventory.index')->name('inventory.index');

// Route Guest (Hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    // Gunakan 'pages::auth.login' (dengan tanda ::)
    Route::livewire('/login', 'pages::auth.login')->name('login');
});

// Route Protected (Wajib Login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
});

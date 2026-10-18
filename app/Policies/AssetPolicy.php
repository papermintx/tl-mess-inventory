<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\AssetPhoto;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Standar Tata Kelola & Keamanan Perbankan (RBAC - Role Based Access Control)
 *
 * Pemisahan Kewenangan (Separation of Duties):
 * - Role 'admin': Pengelola / Administrator Inventaris (Hak CRUD Aset & Manajemen Foto Fisik)
 * - Role 'viewer': Auditor / Peninjau (Hak Baca / Read-Only, Monitoring, dan Inspeksi Fisik)
 */
class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Menentukan apakah pengguna dapat melihat daftar inventaris.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Menentukan apakah pengguna dapat melihat detail & foto fisik aset.
     */
    public function view(User $user, Asset $asset): bool
    {
        return true;
    }

    /**
     * Menentukan apakah pengguna berhak menambahkan aset baru.
     * Standar Bank: Hanya Administrator Pengelola yang berwenang.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Menentukan apakah pengguna berhak memperbarui data aset.
     * Standar Bank: Hanya Administrator Pengelola yang berwenang.
     */
    public function update(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }

    /**
     * Menentukan apakah pengguna berhak menghapus aset dari sistem.
     * Standar Bank: Hanya Administrator Pengelola yang berwenang.
     */
    public function delete(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }

    /**
     * Menentukan apakah pengguna berhak mengunggah dokumentasi foto fisik aset.
     */
    public function uploadPhoto(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }

    /**
     * Menentukan apakah pengguna berhak menghapus foto dokumentasi fisik aset.
     */
    public function deletePhoto(User $user, AssetPhoto $photo): bool
    {
        return $user->isAdmin();
    }
}

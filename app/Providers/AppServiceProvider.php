<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\User;
use App\Policies\AssetPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kebijakan Keamanan Perbankan: Role & Permissions Spatie
        Gate::policy(Asset::class, AssetPolicy::class);

        // Super-admin bypass: user dengan role 'admin' memiliki akses penuh ke sebagian besar kemampuan,
        // KECUALI persetujuan admin yang secara eksklusif hanya untuk Administrator Pertama.
        Gate::before(function (User $user, string $ability): ?bool {
            if ($ability === 'manage-admin-requests') {
                return $user->isFirstAdmin();
            }

            return ($user->hasRole('admin') || $user->isFirstAdmin()) ? true : null;
        });

        Gate::define('manage-admin-requests', function (User $user): bool {
            return $user->isFirstAdmin();
        });

        Gate::define('manage-assets', function (User $user): bool {
            return $user->hasPermissionTo('manage-assets') || $user->isAdmin();
        });

        Gate::define('create-assets', function (User $user): bool {
            return $user->hasPermissionTo('create-assets') || $user->isAdmin();
        });

        Gate::define('edit-assets', function (User $user): bool {
            return $user->hasPermissionTo('edit-assets') || $user->isAdmin();
        });

        Gate::define('delete-assets', function (User $user): bool {
            return $user->hasPermissionTo('delete-assets') || $user->isAdmin();
        });

        Gate::define('upload-asset-photos', function (User $user): bool {
            return $user->hasPermissionTo('upload-asset-photos') || $user->isAdmin();
        });
    }
}

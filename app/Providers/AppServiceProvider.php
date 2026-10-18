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
        // Kebijakan Keamanan Perbankan: Role & Permissions
        Gate::policy(Asset::class, AssetPolicy::class);

        Gate::define('manage-assets', function (User $user): bool {
            return $user->isAdmin();
        });

        Gate::define('create-assets', function (User $user): bool {
            return $user->isAdmin();
        });

        Gate::define('edit-assets', function (User $user): bool {
            return $user->isAdmin();
        });

        Gate::define('delete-assets', function (User $user): bool {
            return $user->isAdmin();
        });

        Gate::define('upload-asset-photos', function (User $user): bool {
            return $user->isAdmin();
        });
    }
}

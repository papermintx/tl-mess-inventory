<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Daftar Permission
        $permissions = [
            'view-inventory',
            'manage-assets',
            'create-assets',
            'edit-assets',
            'delete-assets',
            'upload-asset-photos',
            'delete-asset-photos',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 3. Buat Role Admin & berikan seluruh permission
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        // 4. Buat Role Viewer & berikan permission baca saja
        $viewerRole = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewerRole->syncPermissions(['view-inventory']);

        // 5. Sinkronisasi User yang sudah ada di database ke Role Spatie
        User::all()->each(function (User $user) use ($adminRole, $viewerRole) {
            if ($user->role === 'admin' || $user->username === 'admin') {
                $user->assignRole($adminRole);
            } else {
                $user->assignRole($viewerRole);
            }
        });
    }
}

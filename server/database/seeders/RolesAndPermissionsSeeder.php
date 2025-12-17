<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * ----------------------------------------------------
         * 1. Reset cached roles & permissions
         * ----------------------------------------------------
         */
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * ----------------------------------------------------
         * 2. Create permissions
         * ----------------------------------------------------
         */
        $permissions = [
            'manage_dashboard',
            'manage_products',
            'manage_orders',
            'manage_users',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /**
         * ----------------------------------------------------
         * 3. Create roles
         * ----------------------------------------------------
         */
        $roles = [
            'super_admin',
            'customer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        /**
         * ----------------------------------------------------
         * 4. Assign permissions to roles
         * ----------------------------------------------------
         */
        $role_permissions = [
            'super_admin' => [
                'manage_dashboard',
                'manage_products',
                'manage_orders',
                'manage_users',
                'manage_settings',
            ],

            'customer' => [
                'manage_dashboard',
                'manage_products',
                'manage_orders',
            ],
        ];

        foreach ($role_permissions as $role_name => $permissions) {
            $role = Role::where('name', $role_name)->first();

            if ($role) {
                $role->syncPermissions($permissions);
            }
        }

        /**
         * ----------------------------------------------------
         * 5. Assign roles to users
         * ----------------------------------------------------
         */
        $users_with_roles = [
            'admin@rufars.com' => 'super_admin',
            'customer@test.com' => 'customer',
        ];

        foreach ($users_with_roles as $email => $role) {
            $user = User::where('email', $email)->first();

            if ($user && !$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }
}

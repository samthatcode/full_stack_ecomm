<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Orders
            'view orders',
            'manage orders',

            // Users
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        $admin->givePermissionTo(Permission::all());

        $staff->givePermissionTo([
            'view products',
            'edit products',
            'view orders',
            'manage orders',
        ]);

        $customer->givePermissionTo([
            'view products',
        ]);
    }
}

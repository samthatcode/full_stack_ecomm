<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@rufars.com',
            'password' => 'password',
            'role' => 'super_admin',
            'phone' => '+234 800 000 0000',
            'address' => '123 Admin Street',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'zip_code' => '100001',
            'country' => 'Nigeria',
            'email_verified_at' => now(),
        ]);

        // Test customer
        User::create([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => 'password',
            'role' => 'customer',
            'phone' => '+234 800 111 1111',
            'address' => '456 Customer Avenue',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'zip_code' => '100002',
            'country' => 'Nigeria',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Created admin and test customer users!');
    }
}

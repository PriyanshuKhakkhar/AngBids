<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@larabids.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('super admin');

        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@larabids.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');
    }
}

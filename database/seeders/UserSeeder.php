<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@larabids.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('super admin');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@larabids.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');
    }
}

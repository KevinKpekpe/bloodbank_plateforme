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
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@bloodlink.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'phone_number' => '+243123456789',
            'email_verified_at' => now(),
        ]);

        // Admin Banque 1
        User::create([
            'name' => 'Admin Banque 1',
            'email' => 'admin1@bloodlink.com',
            'password' => Hash::make('password'),
            'role' => 'admin_banque',
            'phone_number' => '+243123456790',
            'email_verified_at' => now(),
        ]);

        // Admin Banque 2
        User::create([
            'name' => 'Admin Banque 2',
            'email' => 'admin2@bloodlink.com',
            'password' => Hash::make('password'),
            'role' => 'admin_banque',
            'phone_number' => '+243123456791',
            'email_verified_at' => now(),
        ]);

        // Donneur 1
        User::create([
            'name' => 'Donneur Test 1',
            'email' => 'donor1@bloodlink.com',
            'password' => Hash::make('password'),
            'role' => 'donor',
            'phone_number' => '+243123456792',
            'email_verified_at' => now(),
        ]);

        // Donneur 2
        User::create([
            'name' => 'Donneur Test 2',
            'email' => 'donor2@bloodlink.com',
            'password' => Hash::make('password'),
            'role' => 'donor',
            'phone_number' => '+243123456793',
            'email_verified_at' => now(),
        ]);

        // Donneur 3
        User::create([
            'name' => 'Donneur Test 3',
            'email' => 'donor3@bloodlink.com',
            'password' => Hash::make('password'),
            'role' => 'donor',
            'phone_number' => '+243123456794',
            'email_verified_at' => now(),
        ]);
    }
}

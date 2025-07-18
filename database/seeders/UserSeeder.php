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
            'name' => 'Dr. Jean-Pierre Mukeba',
            'email' => 'superadmin@bloodlink.cd',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'phone_number' => '+243812345678',
            'email_verified_at' => now(),
        ]);

        // Admin Banque 1 - Centre Hospitalier de Kinshasa
        User::create([
            'name' => 'Dr. Marie-Claire Nzeba',
            'email' => 'admin.chk@bloodlink.cd',
            'password' => Hash::make('password123'),
            'role' => 'admin_banque',
            'phone_number' => '+243823456789',
            'email_verified_at' => now(),
        ]);

        // Admin Banque 2 - Hôpital Général de Kinshasa
        User::create([
            'name' => 'Dr. Pierre Mwamba',
            'email' => 'admin.hgk@bloodlink.cd',
            'password' => Hash::make('password123'),
            'role' => 'admin_banque',
            'phone_number' => '+243834567890',
            'email_verified_at' => now(),
        ]);

        // Admin Banque 3 - Centre Médical de Ngaliema
        User::create([
            'name' => 'Dr. Sarah Kabila',
            'email' => 'admin.cmng@bloodlink.cd',
            'password' => Hash::make('password123'),
            'role' => 'admin_banque',
            'phone_number' => '+243845678901',
            'email_verified_at' => now(),
        ]);

        // Donneur 1
        User::create([
            'name' => 'André Mukendi',
            'email' => 'andre.mukendi@email.cd',
            'password' => Hash::make('password123'),
            'role' => 'donor',
            'phone_number' => '+243856789012',
            'email_verified_at' => now(),
        ]);

        // Donneur 2
        User::create([
            'name' => 'Fatou Diallo',
            'email' => 'fatou.diallo@email.cd',
            'password' => Hash::make('password123'),
            'role' => 'donor',
            'phone_number' => '+243867890123',
            'email_verified_at' => now(),
        ]);

        // Donneur 3
        User::create([
            'name' => 'Marc Tshisekedi',
            'email' => 'marc.tshisekedi@email.cd',
            'password' => Hash::make('password123'),
            'role' => 'donor',
            'phone_number' => '+243878901234',
            'email_verified_at' => now(),
        ]);

        // Donneur 4
        User::create([
            'name' => 'Grace Mwamba',
            'email' => 'grace.mwamba@email.cd',
            'password' => Hash::make('password123'),
            'role' => 'donor',
            'phone_number' => '+243889012345',
            'email_verified_at' => now(),
        ]);
    }
}
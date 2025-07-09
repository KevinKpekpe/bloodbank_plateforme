<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BloodTypeSeeder::class,      // 1. Types de sang
            UserSeeder::class,           // 2. Utilisateurs de base
            BankSeeder::class,           // 3. Banques de sang
            BankAdminSeeder::class,      // 4. Relations admin-banque
            DonorSeeder::class,          // 5. Donneurs avec infos complètes
            BloodStockSeeder::class,     // 6. Stocks de sang
        ]);
    }
}

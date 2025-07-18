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
            UserSeeder::class,           // 2. Utilisateurs (1 superadmin, 3 admins, 4 donneurs)
            BankSeeder::class,           // 3. Banques de sang (3 banques)
            BankAdminSeeder::class,      // 4. Assigner les admins aux banques
            DonorSeeder::class,          // 5. Donneurs avec infos complètes
            AppointmentSeeder::class,    // 6. Rendez-vous
            DonationSeeder::class,       // 7. Dons de sang
            BloodBagSeeder::class,       // 8. Sacs de sang
            BloodStockSeeder::class,     // 9. Stocks de sang
            NotificationSeeder::class,   // 10. Notifications
        ]);
    }
}

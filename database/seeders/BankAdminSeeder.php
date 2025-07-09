<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\BankAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;

class BankAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les admins de banque
        $admin1 = User::where('email', 'admin1@bloodlink.com')->first();
        $admin2 = User::where('email', 'admin2@bloodlink.com')->first();

        // Récupérer les banques
        $bank1 = Bank::where('name', 'Centre Hospitalier de Kinshasa')->first();
        $bank2 = Bank::where('name', 'Hôpital Général de Kinshasa')->first();
        $bank3 = Bank::where('name', 'Centre Médical de Ngaliema')->first();
        $bank4 = Bank::where('name', 'Hôpital Saint Joseph')->first();

        // Admin 1 gère les banques 1 et 2
        BankAdmin::create([
            'user_id' => $admin1->id,
            'bank_id' => $bank1->id,
            'role' => 'admin',
        ]);

        BankAdmin::create([
            'user_id' => $admin1->id,
            'bank_id' => $bank2->id,
            'role' => 'manager',
        ]);

        // Admin 2 gère les banques 3 et 4
        BankAdmin::create([
            'user_id' => $admin2->id,
            'bank_id' => $bank3->id,
            'role' => 'admin',
        ]);

        BankAdmin::create([
            'user_id' => $admin2->id,
            'bank_id' => $bank4->id,
            'role' => 'manager',
        ]);
    }
}

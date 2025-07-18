<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Seeder;

class BankAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les admins
        $admin1 = User::where('email', 'admin.chk@bloodlink.cd')->first();
        $admin2 = User::where('email', 'admin.hgk@bloodlink.cd')->first();
        $admin3 = User::where('email', 'admin.cmng@bloodlink.cd')->first();

        // Récupérer les banques
        $bank1 = Bank::where('name', 'Centre Hospitalier de Kinshasa')->first();
        $bank2 = Bank::where('name', 'Hôpital Général de Kinshasa')->first();
        $bank3 = Bank::where('name', 'Centre Médical de Ngaliema')->first();

        // Assigner les admins aux banques
        $bank1->update(['admin_id' => $admin1->id]);
        $bank2->update(['admin_id' => $admin2->id]);
        $bank3->update(['admin_id' => $admin3->id]);
    }
}
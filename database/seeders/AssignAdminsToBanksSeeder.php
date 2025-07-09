<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AssignAdminsToBanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Banques sans administrateur
        $banksWithoutAdmin = Bank::whereNull('admin_id')->get();

        foreach ($banksWithoutAdmin as $bank) {
            // Créer un administrateur pour cette banque
            $admin = User::create([
                'name' => 'Admin ' . $bank->name,
                'email' => 'admin.' . strtolower(str_replace(' ', '.', $bank->name)) . '@bloodlink.com',
                'phone_number' => '+243123456' . str_pad($bank->id, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role' => 'admin_banque',
                'status' => 'active',
                'email_verified_at' => now()
            ]);

            // Assigner l'administrateur à la banque
            $bank->update(['admin_id' => $admin->id]);

            $this->command->info("Administrateur créé pour {$bank->name}: {$admin->email}");
        }
    }
}

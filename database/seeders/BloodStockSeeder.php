<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\BloodStock;
use App\Models\BloodType;
use Illuminate\Database\Seeder;

class BloodStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les banques
        $banks = Bank::all();

        // Récupérer tous les types de sang
        $bloodTypes = BloodType::all();

        // Pour chaque banque, créer des stocks pour tous les types de sang
        foreach ($banks as $bank) {
            foreach ($bloodTypes as $bloodType) {
                // Générer des quantités aléatoires mais réalistes
                $quantity = rand(5, 50); // Entre 5 et 50 unités
                $criticalLevel = 10; // Seuil critique de 10 unités

                // Déterminer le statut basé sur la quantité
                $status = 'normal';
                if ($quantity <= ($criticalLevel * 0.5)) {
                    $status = 'critical';
                } elseif ($quantity <= $criticalLevel) {
                    $status = 'low';
                } elseif ($quantity > ($criticalLevel * 3)) {
                    $status = 'high';
                }

                BloodStock::create([
                    'bank_id' => $bank->id,
                    'blood_type_id' => $bloodType->id,
                    'quantity' => $quantity,
                    'critical_level' => $criticalLevel,
                    'status' => $status,
                    'last_updated' => now(),
                ]);
            }
        }
    }
}

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
        // Récupérer les banques
        $bank1 = Bank::where('name', 'Centre Hospitalier de Kinshasa')->first();
        $bank2 = Bank::where('name', 'Hôpital Général de Kinshasa')->first();
        $bank3 = Bank::where('name', 'Centre Médical de Ngaliema')->first();

        // Récupérer les types de sang
        $bloodTypes = BloodType::all()->keyBy('name');

        // Stocks pour la Banque 1 - Centre Hospitalier de Kinshasa
        foreach ($bloodTypes as $type) {
            $quantity = $this->getRandomQuantity($type->name);
            BloodStock::create([
                'bank_id' => $bank1->id,
                'blood_type_id' => $type->id,
                'available_bags' => $quantity,
                'reserved_bags' => rand(0, 2),
                'transfused_bags' => rand(5, 15),
                'expired_bags' => rand(1, 5),
                'discarded_bags' => rand(0, 3),
                'total_bags' => $quantity + rand(5, 20),
                'critical_level' => 5,
                'last_updated' => now(),
            ]);
        }

        // Stocks pour la Banque 2 - Hôpital Général de Kinshasa
        foreach ($bloodTypes as $type) {
            $quantity = $this->getRandomQuantity($type->name);
            BloodStock::create([
                'bank_id' => $bank2->id,
                'blood_type_id' => $type->id,
                'available_bags' => $quantity,
                'reserved_bags' => rand(0, 2),
                'transfused_bags' => rand(5, 15),
                'expired_bags' => rand(1, 5),
                'discarded_bags' => rand(0, 3),
                'total_bags' => $quantity + rand(5, 20),
                'critical_level' => 5,
                'last_updated' => now(),
            ]);
        }

        // Stocks pour la Banque 3 - Centre Médical de Ngaliema
        foreach ($bloodTypes as $type) {
            $quantity = $this->getRandomQuantity($type->name);
            BloodStock::create([
                'bank_id' => $bank3->id,
                'blood_type_id' => $type->id,
                'available_bags' => $quantity,
                'reserved_bags' => rand(0, 2),
                'transfused_bags' => rand(5, 15),
                'expired_bags' => rand(1, 5),
                'discarded_bags' => rand(0, 3),
                'total_bags' => $quantity + rand(5, 20),
                'critical_level' => 5,
                'last_updated' => now(),
            ]);
        }
    }

    /**
     * Génère une quantité aléatoire basée sur le type de sang
     */
    private function getRandomQuantity($bloodType)
    {
        // Les types O+ et A+ sont plus courants
        switch ($bloodType) {
            case 'O+':
                return rand(8, 15);
            case 'A+':
                return rand(6, 12);
            case 'B+':
                return rand(4, 8);
            case 'AB+':
                return rand(2, 5);
            case 'O-':
                return rand(3, 6);
            case 'A-':
                return rand(2, 4);
            case 'B-':
                return rand(1, 3);
            case 'AB-':
                return rand(1, 2);
            default:
                return rand(1, 5);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\BloodType;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les types de sang
        $bloodTypes = BloodType::all()->keyBy('name');

        // Récupérer les utilisateurs donneurs
        $donor1 = User::where('email', 'donor1@bloodlink.com')->first();
        $donor2 = User::where('email', 'donor2@bloodlink.com')->first();
        $donor3 = User::where('email', 'donor3@bloodlink.com')->first();

        // Donneur 1 - Jean Mukeba
        Donor::create([
            'user_id' => $donor1->id,
            'firstname' => 'Jean',
            'surname' => 'Mukeba',
            'blood_type_id' => $bloodTypes['O+']->id,
            'gender' => 'male',
            'birthdate' => '1990-05-15',
            'address' => 'Avenue de la Justice, Commune de Gombe, Kinshasa',
            'phone_number' => '+243123456792',
            'last_donation_date' => '2024-01-15',
            'total_donations' => 5,
            'total_volume' => 2.50,
            'status' => 'active',
        ]);

        // Donneur 2 - Marie Nzeba
        Donor::create([
            'user_id' => $donor2->id,
            'firstname' => 'Marie',
            'surname' => 'Nzeba',
            'blood_type_id' => $bloodTypes['A+']->id,
            'gender' => 'female',
            'birthdate' => '1988-12-03',
            'address' => 'Boulevard du 30 Juin, Commune de Limete, Kinshasa',
            'phone_number' => '+243123456793',
            'last_donation_date' => '2024-02-20',
            'total_donations' => 3,
            'total_volume' => 1.50,
            'status' => 'active',
        ]);

        // Donneur 3 - Pierre Mwamba
        Donor::create([
            'user_id' => $donor3->id,
            'firstname' => 'Pierre',
            'surname' => 'Mwamba',
            'blood_type_id' => $bloodTypes['B+']->id,
            'gender' => 'male',
            'birthdate' => '1995-08-22',
            'address' => 'Avenue Colonel Mondjiba, Commune de Ngaliema, Kinshasa',
            'phone_number' => '+243123456794',
            'last_donation_date' => null, // Premier don
            'total_donations' => 0,
            'total_volume' => 0.00,
            'status' => 'active',
        ]);
    }
}

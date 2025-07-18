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
        $donor1 = User::where('email', 'andre.mukendi@email.cd')->first();
        $donor2 = User::where('email', 'fatou.diallo@email.cd')->first();
        $donor3 = User::where('email', 'marc.tshisekedi@email.cd')->first();
        $donor4 = User::where('email', 'grace.mwamba@email.cd')->first();

        // Donneur 1 - André Mukendi
        Donor::create([
            'user_id' => $donor1->id,
            'firstname' => 'André',
            'surname' => 'Mukendi',
            'blood_type_id' => $bloodTypes['O+']->id,
            'gender' => 'male',
            'birthdate' => '1985-03-15',
            'address' => 'Avenue de la Justice, Commune de Gombe, Kinshasa',
            'phone_number' => '+243856789012',
            'last_donation_date' => '2024-01-15',
            'total_donations' => 8,
            'total_volume' => 4.00,
            'status' => 'active',
        ]);

        // Donneur 2 - Fatou Diallo
        Donor::create([
            'user_id' => $donor2->id,
            'firstname' => 'Fatou',
            'surname' => 'Diallo',
            'blood_type_id' => $bloodTypes['A+']->id,
            'gender' => 'female',
            'birthdate' => '1990-07-22',
            'address' => 'Boulevard du 30 Juin, Commune de Limete, Kinshasa',
            'phone_number' => '+243867890123',
            'last_donation_date' => '2024-02-20',
            'total_donations' => 5,
            'total_volume' => 2.50,
            'status' => 'active',
        ]);

        // Donneur 3 - Marc Tshisekedi
        Donor::create([
            'user_id' => $donor3->id,
            'firstname' => 'Marc',
            'surname' => 'Tshisekedi',
            'blood_type_id' => $bloodTypes['B+']->id,
            'gender' => 'male',
            'birthdate' => '1988-11-08',
            'address' => 'Avenue Colonel Mondjiba, Commune de Ngaliema, Kinshasa',
            'phone_number' => '+243878901234',
            'last_donation_date' => '2024-03-10',
            'total_donations' => 3,
            'total_volume' => 1.50,
            'status' => 'active',
        ]);

        // Donneur 4 - Grace Mwamba
        Donor::create([
            'user_id' => $donor4->id,
            'firstname' => 'Grace',
            'surname' => 'Mwamba',
            'blood_type_id' => $bloodTypes['AB+']->id,
            'gender' => 'female',
            'birthdate' => '1992-04-12',
            'address' => 'Avenue de la Libération, Commune de Limete, Kinshasa',
            'phone_number' => '+243889012345',
            'last_donation_date' => null, // Premier don
            'total_donations' => 0,
            'total_volume' => 0.00,
            'status' => 'active',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Bank;
use App\Models\BloodType;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
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

        // Récupérer les donneurs
        $donor1 = Donor::where('firstname', 'André')->first();
        $donor2 = Donor::where('firstname', 'Fatou')->first();
        $donor3 = Donor::where('firstname', 'Marc')->first();

        // Récupérer les types de sang
        $bloodTypes = BloodType::all()->keyBy('name');

        // Récupérer les rendez-vous complétés
        $appointment1 = Appointment::where('donor_id', $donor1->id)->where('status', 'completed')->first();
        $appointment2 = Appointment::where('donor_id', $donor2->id)->where('status', 'completed')->first();
        $appointment3 = Appointment::where('donor_id', $donor3->id)->where('status', 'completed')->first();

        // Don 1 - André Mukendi (O+)
        Donation::create([
            'appointment_id' => $appointment1->id,
            'donor_id' => $donor1->id,
            'bank_id' => $bank1->id,
            'blood_type_id' => $bloodTypes['O+']->id,
            'donation_date' => '2024-01-15',
            'quantity' => 450,
            'volume' => 0.45,
            'status' => 'available',
        ]);

        // Don 2 - Fatou Diallo (A+)
        Donation::create([
            'appointment_id' => $appointment2->id,
            'donor_id' => $donor2->id,
            'bank_id' => $bank2->id,
            'blood_type_id' => $bloodTypes['A+']->id,
            'donation_date' => '2024-02-20',
            'quantity' => 450,
            'volume' => 0.45,
            'status' => 'available',
        ]);

        // Don 3 - Marc Tshisekedi (B+)
        Donation::create([
            'appointment_id' => $appointment3->id,
            'donor_id' => $donor3->id,
            'bank_id' => $bank3->id,
            'blood_type_id' => $bloodTypes['B+']->id,
            'donation_date' => '2024-03-10',
            'quantity' => 450,
            'volume' => 0.45,
            'status' => 'processed',
        ]);

        // Dons historiques pour André Mukendi (pour atteindre 8 dons)
        for ($i = 1; $i <= 7; $i++) {
            $date = now()->subMonths($i * 3)->format('Y-m-d');
            Donation::create([
                'appointment_id' => null,
                'donor_id' => $donor1->id,
                'bank_id' => $bank1->id,
                'blood_type_id' => $bloodTypes['O+']->id,
                'donation_date' => $date,
                'quantity' => 450,
                'volume' => 0.45,
                'status' => 'expired',
            ]);
        }

        // Dons historiques pour Fatou Diallo (pour atteindre 5 dons)
        for ($i = 1; $i <= 4; $i++) {
            $date = now()->subMonths($i * 4)->format('Y-m-d');
            Donation::create([
                'appointment_id' => null,
                'donor_id' => $donor2->id,
                'bank_id' => $bank2->id,
                'blood_type_id' => $bloodTypes['A+']->id,
                'donation_date' => $date,
                'quantity' => 450,
                'volume' => 0.45,
                'status' => 'expired',
            ]);
        }

        // Dons historiques pour Marc Tshisekedi (pour atteindre 3 dons)
        for ($i = 1; $i <= 2; $i++) {
            $date = now()->subMonths($i * 6)->format('Y-m-d');
            Donation::create([
                'appointment_id' => null,
                'donor_id' => $donor3->id,
                'bank_id' => $bank3->id,
                'blood_type_id' => $bloodTypes['B+']->id,
                'donation_date' => $date,
                'quantity' => 450,
                'volume' => 0.45,
                'status' => 'expired',
            ]);
        }
    }
}
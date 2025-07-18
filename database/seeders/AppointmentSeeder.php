<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Bank;
use App\Models\Donor;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
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
        $donor4 = Donor::where('firstname', 'Grace')->first();

        // Rendez-vous passés (complétés)
        Appointment::create([
            'donor_id' => $donor1->id,
            'bank_id' => $bank1->id,
            'appointment_date' => '2024-01-15 09:00:00',
            'status' => 'completed',
            'notes' => 'Donation réussie, volume: 450ml',
        ]);

        Appointment::create([
            'donor_id' => $donor2->id,
            'bank_id' => $bank2->id,
            'appointment_date' => '2024-02-20 14:30:00',
            'status' => 'completed',
            'notes' => 'Donation réussie, volume: 450ml',
        ]);

        Appointment::create([
            'donor_id' => $donor3->id,
            'bank_id' => $bank3->id,
            'appointment_date' => '2024-03-10 10:15:00',
            'status' => 'completed',
            'notes' => 'Donation réussie, volume: 450ml',
        ]);

        // Rendez-vous futurs (confirmés)
        Appointment::create([
            'donor_id' => $donor1->id,
            'bank_id' => $bank1->id,
            'appointment_date' => now()->addDays(7)->setTime(9, 0),
            'status' => 'confirmed',
            'notes' => 'Rendez-vous de suivi',
        ]);

        Appointment::create([
            'donor_id' => $donor4->id,
            'bank_id' => $bank2->id,
            'appointment_date' => now()->addDays(14)->setTime(15, 30),
            'status' => 'confirmed',
            'notes' => 'Premier don',
        ]);

        // Rendez-vous en attente
        Appointment::create([
            'donor_id' => $donor2->id,
            'bank_id' => $bank3->id,
            'appointment_date' => now()->addDays(21)->setTime(11, 0),
            'status' => 'pending',
            'notes' => 'En attente de confirmation',
        ]);

        // Rendez-vous annulés
        Appointment::create([
            'donor_id' => $donor3->id,
            'bank_id' => $bank1->id,
            'appointment_date' => now()->addDays(-5)->setTime(16, 0),
            'status' => 'cancelled',
            'notes' => 'Annulé par le donneur - indisponibilité',
        ]);
    }
}

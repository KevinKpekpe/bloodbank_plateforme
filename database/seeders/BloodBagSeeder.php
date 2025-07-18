<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\BloodBag;
use App\Models\BloodType;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Database\Seeder;

class BloodBagSeeder extends Seeder
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

        // Récupérer les dons récents
        $donation1 = Donation::where('donor_id', $donor1->id)->where('status', 'available')->first();
        $donation2 = Donation::where('donor_id', $donor2->id)->where('status', 'available')->first();
        $donation3 = Donation::where('donor_id', $donor3->id)->where('status', 'processed')->first();

        // Sac de sang 1 - André Mukendi (O+)
        BloodBag::create([
            'donation_id' => $donation1->id,
            'bank_id' => $bank1->id,
            'blood_type_id' => $bloodTypes['O+']->id,
            'donor_id' => $donor1->id,
            'bag_number' => 'BAG-2024-001',
            'volume_ml' => 450,
            'collection_date' => '2024-01-15',
            'expiry_date' => '2024-02-26', // 42 jours après
            'status' => 'available',
            'notes' => 'Sang frais, tests négatifs',
        ]);

        // Sac de sang 2 - Fatou Diallo (A+)
        BloodBag::create([
            'donation_id' => $donation2->id,
            'bank_id' => $bank2->id,
            'blood_type_id' => $bloodTypes['A+']->id,
            'donor_id' => $donor2->id,
            'bag_number' => 'BAG-2024-002',
            'volume_ml' => 450,
            'collection_date' => '2024-02-20',
            'expiry_date' => '2024-04-02', // 42 jours après
            'status' => 'available',
            'notes' => 'Sang frais, tests négatifs',
        ]);

        // Sac de sang 3 - Marc Tshisekedi (B+)
        BloodBag::create([
            'donation_id' => $donation3->id,
            'bank_id' => $bank3->id,
            'blood_type_id' => $bloodTypes['B+']->id,
            'donor_id' => $donor3->id,
            'bag_number' => 'BAG-2024-003',
            'volume_ml' => 450,
            'collection_date' => '2024-03-10',
            'expiry_date' => '2024-04-21', // 42 jours après
            'status' => 'processed',
            'notes' => 'En cours de traitement',
        ]);

        // Sacs de sang réservés
        BloodBag::create([
            'donation_id' => null,
            'bank_id' => $bank1->id,
            'blood_type_id' => $bloodTypes['O+']->id,
            'donor_id' => $donor1->id,
            'bag_number' => 'BAG-2024-004',
            'volume_ml' => 450,
            'collection_date' => '2024-01-10',
            'expiry_date' => '2024-02-21',
            'status' => 'reserved',
            'reserved_for_patient' => 'Patient XYZ',
            'reserved_for_hospital' => 'Hôpital Saint Joseph',
            'reserved_until' => now()->addDays(7),
            'notes' => 'Réservé pour chirurgie cardiaque',
        ]);

        // Sacs de sang transfusés
        BloodBag::create([
            'donation_id' => null,
            'bank_id' => $bank2->id,
            'blood_type_id' => $bloodTypes['A+']->id,
            'donor_id' => $donor2->id,
            'bag_number' => 'BAG-2024-005',
            'volume_ml' => 450,
            'collection_date' => '2024-01-05',
            'expiry_date' => '2024-02-16',
            'status' => 'transfused',
            'notes' => 'Transfusé le 2024-01-20',
        ]);

        // Sacs de sang expirés
        BloodBag::create([
            'donation_id' => null,
            'bank_id' => $bank3->id,
            'blood_type_id' => $bloodTypes['B+']->id,
            'donor_id' => $donor3->id,
            'bag_number' => 'BAG-2023-001',
            'volume_ml' => 450,
            'collection_date' => '2023-12-01',
            'expiry_date' => '2024-01-12',
            'status' => 'expired',
            'notes' => 'Expiré, à détruire',
        ]);

        // Sacs de sang rejetés
        BloodBag::create([
            'donation_id' => null,
            'bank_id' => $bank1->id,
            'blood_type_id' => $bloodTypes['O+']->id,
            'donor_id' => $donor1->id,
            'bag_number' => 'BAG-2024-006',
            'volume_ml' => 450,
            'collection_date' => '2024-02-01',
            'expiry_date' => '2024-03-14',
            'status' => 'discarded',
            'notes' => 'Rejeté - tests positifs',
        ]);
    }
}

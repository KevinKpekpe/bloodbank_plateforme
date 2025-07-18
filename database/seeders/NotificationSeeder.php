<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Donor;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les utilisateurs
        $superAdmin = User::where('role', 'superadmin')->first();
        $admin1 = User::where('email', 'admin.chk@bloodlink.cd')->first();
        $admin2 = User::where('email', 'admin.hgk@bloodlink.cd')->first();
        $admin3 = User::where('email', 'admin.cmng@bloodlink.cd')->first();

        // Récupérer les donneurs
        $donor1 = Donor::where('firstname', 'André')->first();
        $donor2 = Donor::where('firstname', 'Fatou')->first();
        $donor3 = Donor::where('firstname', 'Marc')->first();
        $donor4 = Donor::where('firstname', 'Grace')->first();

        // Récupérer les banques
        $bank1 = Bank::where('name', 'Centre Hospitalier de Kinshasa')->first();
        $bank2 = Bank::where('name', 'Hôpital Général de Kinshasa')->first();
        $bank3 = Bank::where('name', 'Centre Médical de Ngaliema')->first();

        // Notifications pour le super admin
        Notification::create([
            'user_id' => $superAdmin->id,
            'title' => 'Nouvelle banque de sang créée',
            'message' => 'La banque Centre Hospitalier de Kinshasa a été ajoutée au système.',
            'type' => 'info',
            'read' => false,
            'data' => json_encode(['bank_id' => $bank1->id]),
        ]);

        Notification::create([
            'user_id' => $superAdmin->id,
            'title' => 'Stock critique détecté',
            'message' => 'Le stock de sang O- est critique à l\'Hôpital Général de Kinshasa.',
            'type' => 'warning',
            'read' => false,
            'data' => json_encode(['bank_id' => $bank2->id, 'blood_type' => 'O-']),
        ]);

        // Notifications pour les admins de banque
        Notification::create([
            'user_id' => $admin1->id,
            'title' => 'Nouveau donneur enregistré',
            'message' => 'André Mukendi s\'est inscrit comme donneur.',
            'type' => 'info',
            'read' => false,
            'data' => json_encode(['donor_id' => $donor1->id]),
        ]);

        Notification::create([
            'user_id' => $admin2->id,
            'title' => 'Rendez-vous confirmé',
            'message' => 'Fatou Diallo a confirmé son rendez-vous pour le 15 février.',
            'type' => 'success',
            'read' => false,
            'data' => json_encode(['donor_id' => $donor2->id]),
        ]);

        Notification::create([
            'user_id' => $admin3->id,
            'title' => 'Don de sang reçu',
            'message' => 'Marc Tshisekedi a effectué un don de sang de 450ml.',
            'type' => 'success',
            'read' => false,
            'data' => json_encode(['donor_id' => $donor3->id]),
        ]);

        // Notifications pour les donneurs
        Notification::create([
            'user_id' => $donor1->user_id,
            'title' => 'Rappel de don',
            'message' => 'Vous pouvez faire un nouveau don de sang. Votre dernier don remonte à 3 mois.',
            'type' => 'reminder',
            'read' => false,
            'data' => json_encode(['last_donation' => '2024-01-15']),
        ]);

        Notification::create([
            'user_id' => $donor2->user_id,
            'title' => 'Rendez-vous confirmé',
            'message' => 'Votre rendez-vous du 20 février a été confirmé.',
            'type' => 'success',
            'read' => false,
            'data' => json_encode(['appointment_date' => '2024-02-20']),
        ]);

        Notification::create([
            'user_id' => $donor3->user_id,
            'title' => 'Merci pour votre don',
            'message' => 'Merci pour votre don de sang du 10 mars. Votre sang a été traité avec succès.',
            'type' => 'success',
            'read' => false,
            'data' => json_encode(['donation_date' => '2024-03-10']),
        ]);

        Notification::create([
            'user_id' => $donor4->user_id,
            'title' => 'Bienvenue',
            'message' => 'Bienvenue dans notre système de don de sang. Nous vous remercions de votre inscription.',
            'type' => 'welcome',
            'read' => false,
            'data' => json_encode(['registration_date' => now()->format('Y-m-d')]),
        ]);

        // Notifications lues
        Notification::create([
            'user_id' => $superAdmin->id,
            'title' => 'Système mis à jour',
            'message' => 'Le système a été mis à jour vers la version 2.1.0.',
            'type' => 'info',
            'read' => true,
            'data' => json_encode(['version' => '2.1.0']),
        ]);

        Notification::create([
            'user_id' => $admin1->id,
            'title' => 'Formation programmée',
            'message' => 'Une formation sur les nouvelles procédures est programmée pour le 25 janvier.',
            'type' => 'info',
            'read' => true,
            'data' => json_encode(['training_date' => '2024-01-25']),
        ]);
    }
}

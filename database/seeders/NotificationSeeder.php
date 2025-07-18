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
            'donor_id' => null,
            'bank_id' => $bank1->id,
            'type' => 'new_appointment',
            'title' => 'Nouvelle banque de sang créée',
            'message' => 'La banque Centre Hospitalier de Kinshasa a été ajoutée au système.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $superAdmin->id,
            'donor_id' => null,
            'bank_id' => $bank2->id,
            'type' => 'critical_stock',
            'title' => 'Stock critique détecté',
            'message' => 'Le stock de sang O- est critique à l\'Hôpital Général de Kinshasa.',
            'is_read' => false,
        ]);

        // Notifications pour les admins de banque
        Notification::create([
            'user_id' => $admin1->id,
            'donor_id' => $donor1->id,
            'bank_id' => $bank1->id,
            'type' => 'new_appointment',
            'title' => 'Nouveau donneur enregistré',
            'message' => 'André Mukendi s\'est inscrit comme donneur.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $admin2->id,
            'donor_id' => $donor2->id,
            'bank_id' => $bank2->id,
            'type' => 'new_appointment',
            'title' => 'Rendez-vous confirmé',
            'message' => 'Fatou Diallo a confirmé son rendez-vous pour le 15 février.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $admin3->id,
            'donor_id' => $donor3->id,
            'bank_id' => $bank3->id,
            'type' => 'donation_completed',
            'title' => 'Don de sang reçu',
            'message' => 'Marc Tshisekedi a effectué un don de sang de 450ml.',
            'is_read' => false,
        ]);

        // Notifications pour les donneurs
        Notification::create([
            'user_id' => $donor1->user_id,
            'donor_id' => $donor1->id,
            'bank_id' => $bank1->id,
            'type' => 'new_appointment',
            'title' => 'Rappel de don',
            'message' => 'Vous pouvez faire un nouveau don de sang. Votre dernier don remonte à 3 mois.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $donor2->user_id,
            'donor_id' => $donor2->id,
            'bank_id' => $bank2->id,
            'type' => 'new_appointment',
            'title' => 'Rendez-vous confirmé',
            'message' => 'Votre rendez-vous du 20 février a été confirmé.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $donor3->user_id,
            'donor_id' => $donor3->id,
            'bank_id' => $bank3->id,
            'type' => 'donation_completed',
            'title' => 'Merci pour votre don',
            'message' => 'Merci pour votre don de sang du 10 mars. Votre sang a été traité avec succès.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $donor4->user_id,
            'donor_id' => $donor4->id,
            'bank_id' => $bank1->id,
            'type' => 'new_appointment',
            'title' => 'Bienvenue',
            'message' => 'Bienvenue dans notre système de don de sang. Nous vous remercions de votre inscription.',
            'is_read' => false,
        ]);

        // Notifications lues
        Notification::create([
            'user_id' => $superAdmin->id,
            'donor_id' => null,
            'bank_id' => null,
            'type' => 'new_appointment',
            'title' => 'Système mis à jour',
            'message' => 'Le système a été mis à jour vers la version 2.1.0.',
            'is_read' => true,
            'read_at' => now(),
        ]);

        Notification::create([
            'user_id' => $admin1->id,
            'donor_id' => null,
            'bank_id' => $bank1->id,
            'type' => 'new_appointment',
            'title' => 'Formation programmée',
            'message' => 'Une formation sur les nouvelles procédures est programmée pour le 25 janvier.',
            'is_read' => true,
            'read_at' => now(),
        ]);

        // Notifications de stock faible
        Notification::create([
            'user_id' => $admin1->id,
            'donor_id' => null,
            'bank_id' => $bank1->id,
            'type' => 'low_stock',
            'title' => 'Stock faible détecté',
            'message' => 'Le stock de sang A- est faible au Centre Hospitalier de Kinshasa.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $admin2->id,
            'donor_id' => null,
            'bank_id' => $bank2->id,
            'type' => 'low_stock',
            'title' => 'Stock faible détecté',
            'message' => 'Le stock de sang B+ est faible à l\'Hôpital Général de Kinshasa.',
            'is_read' => false,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le super admin
        $superAdmin = User::where('role', 'superadmin')->first();

        // Banque 1 - Centre Hospitalier de Kinshasa
        Bank::create([
            'name' => 'Centre Hospitalier de Kinshasa',
            'address' => 'Avenue de la Justice, Commune de Gombe, Kinshasa',
            'latitude' => -4.3224,
            'longitude' => 15.3075,
            'contact_phone' => '+243123456700',
            'contact_email' => 'contact@chk.kinshasa.cd',
            'status' => 'active',
            'created_by' => $superAdmin->id,
        ]);

        // Banque 2 - Hôpital Général de Kinshasa
        Bank::create([
            'name' => 'Hôpital Général de Kinshasa',
            'address' => 'Boulevard du 30 Juin, Commune de Limete, Kinshasa',
            'latitude' => -4.3158,
            'longitude' => 15.3115,
            'contact_phone' => '+243123456701',
            'contact_email' => 'contact@hgk.kinshasa.cd',
            'status' => 'active',
            'created_by' => $superAdmin->id,
        ]);

        // Banque 3 - Centre Médical de Ngaliema
        Bank::create([
            'name' => 'Centre Médical de Ngaliema',
            'address' => 'Avenue Colonel Mondjiba, Commune de Ngaliema, Kinshasa',
            'latitude' => -4.3289,
            'longitude' => 15.2954,
            'contact_phone' => '+243123456702',
            'contact_email' => 'contact@cmng.kinshasa.cd',
            'status' => 'active',
            'created_by' => $superAdmin->id,
        ]);

        // Banque 4 - Hôpital Saint Joseph
        Bank::create([
            'name' => 'Hôpital Saint Joseph',
            'address' => 'Avenue de la Libération, Commune de Limete, Kinshasa',
            'latitude' => -4.3187,
            'longitude' => 15.3089,
            'contact_phone' => '+243123456703',
            'contact_email' => 'contact@hsj.kinshasa.cd',
            'status' => 'active',
            'created_by' => $superAdmin->id,
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convertir les dons disponibles en poches
        $donations = DB::table('donations')
            ->where('status', 'available')
            ->get();

        foreach ($donations as $donation) {
            $bagsCount = floor($donation->quantity / 450); // 450ml par poche
            $remainingVolume = $donation->quantity % 450;

            for ($i = 0; $i < $bagsCount; $i++) {
                $bagNumber = 'BAG-' . date('Y') . '-' . str_pad(DB::table('blood_bags')->count() + 1, 3, '0', STR_PAD_LEFT);

                DB::table('blood_bags')->insert([
                    'donation_id' => $donation->id,
                    'bank_id' => $donation->bank_id,
                    'blood_type_id' => $donation->blood_type_id,
                    'donor_id' => $donation->donor_id,
                    'bag_number' => $bagNumber,
                    'volume_ml' => 450,
                    'collection_date' => $donation->donation_date,
                    'expiry_date' => Carbon::parse($donation->donation_date)->addDays(42),
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Si il reste du volume, créer une poche partielle
            if ($remainingVolume > 0) {
                $bagNumber = 'BAG-' . date('Y') . '-' . str_pad(DB::table('blood_bags')->count() + 1, 3, '0', STR_PAD_LEFT);

                DB::table('blood_bags')->insert([
                    'donation_id' => $donation->id,
                    'bank_id' => $donation->bank_id,
                    'blood_type_id' => $donation->blood_type_id,
                    'donor_id' => $donation->donor_id,
                    'bag_number' => $bagNumber,
                    'volume_ml' => $remainingVolume,
                    'collection_date' => $donation->donation_date,
                    'expiry_date' => Carbon::parse($donation->donation_date)->addDays(42),
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer toutes les poches créées
        DB::table('blood_bags')->truncate();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Recalculer les statistiques basées sur les poches
        $stocks = DB::table('blood_stocks')->get();

        foreach ($stocks as $stock) {
            $totalBags = DB::table('blood_bags')
                ->where('bank_id', $stock->bank_id)
                ->where('blood_type_id', $stock->blood_type_id)
                ->whereIn('status', ['available', 'reserved'])
                ->count();

            $availableBags = DB::table('blood_bags')
                ->where('bank_id', $stock->bank_id)
                ->where('blood_type_id', $stock->blood_type_id)
                ->where('status', 'available')
                ->count();

            $reservedBags = DB::table('blood_bags')
                ->where('bank_id', $stock->bank_id)
                ->where('blood_type_id', $stock->blood_type_id)
                ->where('status', 'reserved')
                ->count();

            $expiringSoonBags = DB::table('blood_bags')
                ->where('bank_id', $stock->bank_id)
                ->where('blood_type_id', $stock->blood_type_id)
                ->where('status', 'available')
                ->where('expiry_date', '<=', now()->addDays(7))
                ->count();

            DB::table('blood_stocks')
                ->where('id', $stock->id)
                ->update([
                    'total_bags' => $totalBags,
                    'available_bags' => $availableBags,
                    'reserved_bags' => $reservedBags,
                    'expiring_soon_bags' => $expiringSoonBags,
                    'quantity' => $totalBags * 450, // Pour compatibilité
                    'last_updated' => now()
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les statistiques à zéro
        DB::table('blood_stocks')->update([
            'total_bags' => 0,
            'available_bags' => 0,
            'reserved_bags' => 0,
            'expiring_soon_bags' => 0
        ]);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les seuils critiques avec des valeurs plus réalistes
        // Seuil critique: 500ml (0.5L) - niveau d'alerte
        DB::table('blood_stocks')->update([
            'critical_level' => 500, // 500ml = 0.5L
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les anciens seuils
        DB::table('blood_stocks')->update([
            'critical_level' => 10,
        ]);
    }
};

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
        // Migrer les données de bank_admins vers banks
        // Pour chaque banque, prendre le premier admin (rôle 'admin' prioritaire)
        $bankAdmins = DB::table('bank_admins')
            ->orderBy('role', 'desc') // 'admin' avant 'manager'
            ->orderBy('created_at', 'asc')
            ->get();

        $processedBanks = [];

        foreach ($bankAdmins as $bankAdmin) {
            // Vérifier si cette banque a déjà été traitée
            if (in_array($bankAdmin->bank_id, $processedBanks)) {
                continue;
            }

            // Vérifier si cet admin gère déjà une autre banque
            $existingBank = DB::table('banks')
                ->where('admin_id', $bankAdmin->user_id)
                ->first();

            if ($existingBank) {
                continue; // Cet admin gère déjà une banque, on passe au suivant
            }

            // Assigner cet admin à cette banque
            DB::table('banks')
                ->where('id', $bankAdmin->bank_id)
                ->update(['admin_id' => $bankAdmin->user_id]);

            $processedBanks[] = $bankAdmin->bank_id;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Réinitialiser admin_id à null pour toutes les banques
        DB::table('banks')->update(['admin_id' => null]);
    }
};

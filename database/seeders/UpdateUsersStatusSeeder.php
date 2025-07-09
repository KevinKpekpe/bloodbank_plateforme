<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateUsersStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mettre à jour tous les utilisateurs existants avec le statut 'active'
        User::whereNull('status')->update(['status' => 'active']);

        // Mettre à jour les utilisateurs avec email_verified_at mais sans status
        User::whereNotNull('email_verified_at')
            ->whereNull('status')
            ->update(['status' => 'active']);

        // Mettre à jour les utilisateurs sans email_verified_at avec le statut 'inactive'
        User::whereNull('email_verified_at')
            ->whereNull('status')
            ->update(['status' => 'inactive']);
    }
}

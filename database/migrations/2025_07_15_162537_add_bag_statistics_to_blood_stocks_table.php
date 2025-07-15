<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            $table->integer('total_bags')->default(0)->after('quantity');
            $table->integer('available_bags')->default(0)->after('total_bags');
            $table->integer('reserved_bags')->default(0)->after('available_bags');
            $table->integer('expiring_soon_bags')->default(0)->after('reserved_bags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            $table->dropColumn([
                'total_bags',
                'available_bags',
                'reserved_bags',
                'expiring_soon_bags'
            ]);
        });
    }
};

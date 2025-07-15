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
            $table->integer('transfused_bags')->default(0)->after('reserved_bags');
            $table->integer('expired_bags')->default(0)->after('transfused_bags');
            $table->integer('discarded_bags')->default(0)->after('expired_bags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_stocks', function (Blueprint $table) {
            $table->dropColumn(['transfused_bags', 'expired_bags', 'discarded_bags']);
        });
    }
};

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
        Schema::create('blood_bag_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bag_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->enum('movement_type', ['in', 'out', 'reservation', 'cancellation', 'transfer']);
            $table->integer('quantity')->default(1);
            $table->enum('recipient_type', ['patient', 'hospital', 'other_bank', 'discard']);
            $table->string('recipient_name', 255)->nullable();
            $table->string('recipient_id', 100)->nullable();
            $table->text('reason')->nullable();
            $table->string('requested_by', 255)->nullable();
            $table->string('authorized_by', 255)->nullable();
            $table->dateTime('movement_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['blood_bag_id', 'movement_date']);
            $table->index(['bank_id', 'movement_type']);
            $table->index('movement_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_bag_movements');
    }
};

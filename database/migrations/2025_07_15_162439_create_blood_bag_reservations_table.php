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
        Schema::create('blood_bag_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bag_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->string('patient_name', 255);
            $table->string('patient_id', 100)->nullable();
            $table->string('hospital_name', 255)->nullable();
            $table->string('doctor_name', 255)->nullable();
            $table->dateTime('reservation_date');
            $table->dateTime('expiry_date'); // Date limite de réservation
            $table->enum('status', ['active', 'completed', 'cancelled', 'expired'])->default('active');
            $table->enum('urgency_level', ['normal', 'urgent', 'critical'])->default('normal');
            $table->date('surgery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['blood_bag_id', 'status']);
            $table->index(['bank_id', 'status']);
            $table->index(['expiry_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_bag_reservations');
    }
};

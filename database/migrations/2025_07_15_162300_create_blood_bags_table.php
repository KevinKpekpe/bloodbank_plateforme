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
        Schema::create('blood_bags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->foreignId('blood_type_id')->constrained('blood_types');
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->string('bag_number', 50)->unique(); // BAG-2024-001
            $table->integer('volume_ml')->default(450); // Volume standard
            $table->date('collection_date');
            $table->date('expiry_date'); // 42 jours après collecte
            $table->enum('status', ['collected', 'processed', 'available', 'reserved', 'transfused', 'expired', 'discarded'])->default('collected');
            $table->string('reserved_for_patient', 255)->nullable();
            $table->string('reserved_for_hospital', 255)->nullable();
            $table->timestamp('reserved_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['bank_id', 'blood_type_id', 'status']);
            $table->index(['expiry_date', 'status']);
            $table->index('bag_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_bags');
    }
};

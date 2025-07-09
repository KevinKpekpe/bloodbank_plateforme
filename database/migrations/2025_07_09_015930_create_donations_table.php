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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->foreignId('blood_type_id')->constrained('blood_types');
            $table->date('donation_date');
            $table->integer('quantity'); // en ml
            $table->decimal('volume', 5, 2); // en litres
            $table->enum('status', ['collected', 'processed', 'available', 'expired'])->default('collected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};

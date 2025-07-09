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
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('firstname');
            $table->string('surname');
            $table->foreignId('blood_type_id')->constrained('blood_types');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birthdate');
            $table->string('address');
            $table->string('phone_number', 20);
            $table->date('last_donation_date')->nullable();
            $table->integer('total_donations')->default(0);
            $table->decimal('total_volume', 5, 2)->default(0.00); // en litres
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};

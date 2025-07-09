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
        Schema::create('blood_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->foreignId('blood_type_id')->constrained('blood_types');
            $table->integer('quantity')->default(0);
            $table->integer('critical_level')->default(10);
            $table->enum('status', ['high', 'low', 'normal', 'critical'])->default('normal');
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            $table->unique(['bank_id', 'blood_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_stocks');
    }
};

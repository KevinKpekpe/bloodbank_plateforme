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
        Schema::dropIfExists('bank_admins');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('bank_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'manager'])->default('admin');
            $table->timestamps();

            $table->unique(['user_id', 'bank_id']);
        });
    }
};

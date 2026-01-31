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
        Schema::create('locker_verifications', function (Blueprint $table) {
            $table->id();

            // References lockers table
            $table->foreignId('locker_id')
                  ->constrained('lockers')
                  ->onDelete('cascade');

            // Verification stage
            $table->enum('stage', ['before_storage', 'after_storage']);

            // User who verified (nullable)
            $table->foreignId('verified_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            // Index for faster queries
            $table->index(['locker_id', 'stage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_verifications');
    }
};

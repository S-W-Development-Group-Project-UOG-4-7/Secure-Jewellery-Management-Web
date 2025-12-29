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
        Schema::create('locker_verification_items', function (Blueprint $table) {
            $table->id();

            // Reference to locker_verifications
            $table->foreignId('locker_verification_id')
                  ->constrained('locker_verifications')
                  ->onDelete('cascade');

            // Reference to jewellery
            $table->foreignId('jewellery_id')
                  ->constrained('jewellery')
                  ->onDelete('cascade');

            // Verification result
            $table->enum('result', ['pass', 'fail'])->default('pass');

            $table->text('remarks')->nullable();

            // Proof image path (before/after storage)
            $table->string('proof_image')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index(['jewellery_id', 'result']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_verification_items');
    }
};

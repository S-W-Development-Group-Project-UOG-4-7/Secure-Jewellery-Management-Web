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
        Schema::create('custom_notifications', function (Blueprint $table) {
            $table->id();

            // Link notification to a user
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Notification content
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('type')->nullable(); // e.g. "system", "report", "backup"
            $table->string('url')->nullable();  // URL to redirect on click

            // Read status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Index for faster querying
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_notifications');
    }
};

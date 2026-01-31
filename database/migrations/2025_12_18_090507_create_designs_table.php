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
        Schema::create('designs', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // AI design details
            $table->text('prompt');            // AI instruction
            $table->string('image_url');       // Generated image URL
            $table->string('style')->nullable();
            $table->string('metal')->nullable();
            $table->string('gemstone')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};

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
        Schema::create('jewellery', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('type')->nullable();        // ring, necklace, bracelet, etc.
            $table->string('metal')->nullable();       // gold, silver, platinum
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('value', 10, 2)->nullable();

            $table->string('status')->default('available'); 
            // available | reserved | stored | sold

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewellery');
    }
};

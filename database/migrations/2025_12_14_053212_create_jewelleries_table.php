<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jewelleries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // Ring, Necklace, etc.
            $table->string('material'); // Gold, Platinum, etc.
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->string('image_path')->nullable(); // Path to image file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jewelleries');
    }
};
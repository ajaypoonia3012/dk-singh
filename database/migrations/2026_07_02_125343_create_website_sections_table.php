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
        Schema::create('website_sections', function (Blueprint $table) {

            $table->id();

            // Page Name
            $table->string('page')->default('home');

            // Section Identifier
            $table->string('section');

            // Display Name
            $table->string('title');

            // Enable / Disable
            $table->boolean('enabled')->default(true);

            // Order on page
            $table->integer('sort_order')->default(1);

            // Settings for this section
            $table->json('settings')->nullable();

            // Optional background image
            $table->string('background')->nullable();

            // Theme / Template
            $table->string('template')->default('default');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_sections');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            $table->string('hero_title')->nullable();

            $table->text('hero_subtitle')->nullable();

            $table->string('hero_image')->nullable();

            $table->string('instagram_followers')->nullable();

            $table->string('years_experience')->nullable();

            $table->string('transformations')->nullable();

            $table->string('cta_button_text')->nullable();

            $table->string('cta_button_link')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
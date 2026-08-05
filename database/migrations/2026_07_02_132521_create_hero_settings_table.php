<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_settings', function (Blueprint $table) {

            $table->id();

            $table->string('heading');

            $table->text('subheading')->nullable();

            $table->string('button_text')->nullable();

            $table->string('button_link')->nullable();

            $table->string('background')->nullable();

            $table->string('video')->nullable();

            $table->string('overlay_color')->default('#000000');

            $table->integer('overlay_opacity')->default(40);

            $table->string('template')->default('modern');

            $table->boolean('enabled')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
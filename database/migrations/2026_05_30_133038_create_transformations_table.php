<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transformations', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('before_image');

            $table->string('after_image');

            $table->string('duration')->nullable();

            $table->string('weight_loss')->nullable();

            $table->text('story')->nullable();

            $table->boolean('featured')->default(false);

            $table->boolean('status')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transformations');
    }
};
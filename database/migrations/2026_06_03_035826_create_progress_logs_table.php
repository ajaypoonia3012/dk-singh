<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | BODY STATS
            |--------------------------------------------------------------------------
            */

            $table->decimal('weight', 5, 2)
                ->nullable();

            $table->decimal('bmi', 5, 2)
                ->nullable();

            $table->decimal('body_fat', 5, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | MEASUREMENTS
            |--------------------------------------------------------------------------
            */

            $table->decimal('chest', 5, 2)
                ->nullable();

            $table->decimal('waist', 5, 2)
                ->nullable();

            $table->decimal('arms', 5, 2)
                ->nullable();

            $table->decimal('thighs', 5, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | PROGRESS PHOTOS
            |--------------------------------------------------------------------------
            */

            $table->string('front_photo')
                ->nullable();

            $table->string('side_photo')
                ->nullable();

            $table->string('back_photo')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | USER NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_logs');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('phone')->nullable();

            $table->string('whatsapp_number')->nullable();

            $table->string('gender')->nullable();

            $table->integer('age')->nullable();

            $table->decimal('height', 5, 2)->nullable();

            $table->decimal('weight', 5, 2)->nullable();

            $table->decimal('bmi', 5, 2)->nullable();

            $table->string('goal')->nullable();

            $table->string('activity_level')->nullable();

            $table->string('diet_preference')->nullable();

            $table->text('allergies')->nullable();

            $table->text('medical_conditions')->nullable();

            $table->string('city')->nullable();

            $table->string('country')->nullable();

            $table->string('profile_photo')->nullable();

            $table->text('bio')->nullable();

            $table->boolean('is_premium')->default(false);

            $table->boolean('is_coach')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([

                'phone',
                'whatsapp_number',
                'gender',
                'age',
                'height',
                'weight',
                'bmi',
                'goal',
                'activity_level',
                'diet_preference',
                'allergies',
                'medical_conditions',
                'city',
                'country',
                'profile_photo',
                'bio',
                'is_premium',
                'is_coach',

            ]);

        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->string('exercise_category')->index(); // Chest, Back, Shoulders, Legs, Arms, Core, Full Body, Mobility, Cardio
            $table->string('primary_muscle')->index(); // Pectorals, Lats, Quadriceps, etc.
            $table->json('secondary_muscles')->nullable();
            $table->string('equipment')->default('Bodyweight')->index(); // Bodyweight, Barbell, Dumbbell, etc.
            $table->string('difficulty')->default('Beginner')->index(); // Beginner, Intermediate, Advanced
            $table->string('movement_pattern')->default('Push')->index(); // Push, Pull, Squat, Hinge, etc.
            $table->text('setup')->nullable();
            $table->json('execution_steps')->nullable();
            $table->text('breathing_guidance')->nullable();
            $table->json('common_mistakes')->nullable();
            $table->text('safety_considerations')->nullable();
            $table->text('beginner_modification')->nullable();
            $table->text('advanced_variation')->nullable();
            $table->text('home_variation')->nullable();
            $table->text('gym_variation')->nullable();
            $table->string('video_url')->nullable();
            $table->json('faqs')->nullable();
            $table->boolean('featured')->default(false)->index();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('views')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};

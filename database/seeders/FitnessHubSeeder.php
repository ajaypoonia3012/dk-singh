<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;
use App\Models\Blog;
use App\Models\Transformation;

class FitnessHubSeeder extends Seeder
{
    public function run(): void
    {
        // Workouts

        for ($i = 1; $i <= 20; $i++) {

            WorkoutPlan::create([
                'title' => "Weight Loss Workout $i",
                'description' => "Free workout plan for weight loss.",
                'content' => "<h2>Workout $i</h2><p>Full workout content.</p>",
                'difficulty' => 'Beginner',
                'category' => 'weight_loss',
                'required_access' => 'public',
            ]);
        }

        // Diets

        for ($i = 1; $i <= 20; $i++) {

            DietPlan::create([
                'title' => "Diet Plan $i",
                'description' => "Healthy diet plan.",
                'content' => "<h2>Diet $i</h2><p>Diet details.</p>",
                'goal' => 'Weight Loss',
                'status' => 1,
                'required_access' => 'public',
            ]);
        }

        // Blogs

        for ($i = 1; $i <= 20; $i++) {

            Blog::create([
                'title' => "Fitness Article $i",
                'content' => "<h2>Fitness Article $i</h2><p>SEO fitness article.</p>",
                'status' => 1,
            ]);
        }

        // Transformations

        for ($i = 1; $i <= 10; $i++) {

            Transformation::create([
                'name' => "Client $i",
                'duration' => "$i Months",
                'weight_loss' => rand(5,20) . " KG",
                'story' => "Amazing transformation story.",
                'status' => 1,
            ]);
        }
    }
}
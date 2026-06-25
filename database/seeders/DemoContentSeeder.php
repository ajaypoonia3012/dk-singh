<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Program;
use App\Models\Service;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Transformation;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | PROGRAMS
        |--------------------------------------------------------------------------
        */

        Program::insert([

            [
                'title' => 'Fat Loss Transformation',
                'slug' => 'fat-loss-transformation',
'category' => 'Fitness',
                'description' => 'Complete fat loss coaching program.',
                'image' => 'programs/default.jpg',
                'price' => 2999,
                'duration' => '8 Weeks',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'Muscle Gain Program',
                'slug' => 'muscle-gain-program',
'category' => 'Fitness',
                'description' => 'Build lean muscle and strength.',
                'image' => 'programs/default.jpg',
                'price' => 3999,
                'duration' => '12 Weeks',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

        Service::insert([

            [
                'title' => 'Personal Coaching',
                'slug' => 'personal-coaching',
                'description' => '1-on-1 personal fitness coaching.',
                'image' => 'services/default.jpg',
                'price' => 4999,
                'duration' => 'Monthly',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'Online Consultation',
                'slug' => 'online-consultation',
                'description' => 'Video consultation with DK Singh.',
                'image' => 'services/default.jpg',
                'price' => 1999,
                'duration' => 'Single Session',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        Product::insert([

            [
                'name' => 'Whey Protein',
                'slug' => 'whey-protein',
                'description' => 'Premium whey protein supplement.',
                'image' => 'products/default.jpg',
                'price' => 2499,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Pre Workout',
                'slug' => 'pre-workout',
                'description' => 'Energy booster for intense workouts.',
                'image' => 'products/default.jpg',
                'price' => 1499,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | BLOGS
        |--------------------------------------------------------------------------
        */

        Blog::insert([

            [
                'title' => 'Best Fat Loss Tips',
                'slug' => 'best-fat-loss-tips',
                
                'content' => 'Detailed fat loss blog content goes here.',
                'image' => 'blogs/default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'title' => 'Muscle Building Guide',
                'slug' => 'muscle-building-guide',
                
                'content' => 'Detailed muscle building content goes here.',
                'image' => 'blogs/default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | TRANSFORMATIONS
        |--------------------------------------------------------------------------
        */

        Transformation::insert([

            [
                'name' => 'Rahul Sharma',
                'description' => 'Lost 18kg in 4 months.',
                'image' => 'transformations/default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Aman Verma',
                'description' => 'Built lean muscle naturally.',
                'image' => 'transformations/default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
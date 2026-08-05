<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [

            'blog_category_id' => BlogCategory::inRandomOrder()->value('id'),

            'media_id' => Media::where('active', true)
                ->inRandomOrder()
                ->value('id'),

            'title' => $title,

            'slug' => Str::slug($title),

            'excerpt' => fake()->paragraph(),

            'content' => collect(range(1, 10))
                ->map(fn () => '<p>'.fake()->paragraph(6).'</p>')
                ->implode("\n"),

            'author' => 'DK Singh',

            'reading_time' => fake()->numberBetween(3, 15),

            'views' => fake()->numberBetween(100, 50000),

            'featured' => fake()->boolean(25),

            'status' => true,

            'published_at' => fake()->dateTimeBetween('-1 year'),

            'seo_title' => $title,

            'seo_description' => fake()->sentence(),

        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogCategoryFactory extends Factory
{
    protected $model = BlogCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'featured' => fake()->boolean(20),
            'status' => true,
            'sort_order' => fake()->numberBetween(1, 20),
            'seo_title' => ucwords($name),
            'seo_description' => fake()->sentence(),
        ];
    }
}
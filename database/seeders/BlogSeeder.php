<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        if (BlogCategory::count() === 0) {
            BlogCategory::factory()
                ->count(5)
                ->create();
        }

        if (BlogTag::count() === 0) {
            BlogTag::factory()
                ->count(10)
                ->create();
        }

        if (BlogPost::count() === 0) {

            BlogPost::factory()
                ->count(20)
                ->create()
                ->each(function (BlogPost $post) {

                    $post->tags()->attach(

                        BlogTag::inRandomOrder()
                            ->take(rand(2, 5))
                            ->pluck('id')
                            ->toArray()

                    );

                });

        }
    }
}
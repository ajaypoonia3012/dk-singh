<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MediaCategory;

class MediaCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Hero',
                'slug' => 'hero',
                'icon' => 'heroicon-o-photo',
                'description' => 'Hero section images',
                'sort_order' => 1,
            ],

            [
                'name' => 'Homepage Cards',
                'slug' => 'homepage-cards',
                'icon' => 'heroicon-o-squares-2x2',
                'description' => 'Homepage card images',
                'sort_order' => 2,
            ],

            [
                'name' => 'Programs',
                'slug' => 'programs',
                'icon' => 'heroicon-o-fire',
                'description' => 'Program images',
                'sort_order' => 3,
            ],

            [
                'name' => 'Products',
                'slug' => 'products',
                'icon' => 'heroicon-o-shopping-bag',
                'description' => 'Product images',
                'sort_order' => 4,
            ],

            [
                'name' => 'Blogs',
                'slug' => 'blogs',
                'icon' => 'heroicon-o-document-text',
                'description' => 'Blog featured images',
                'sort_order' => 5,
            ],

            [
                'name' => 'Testimonials',
                'slug' => 'testimonials',
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'description' => 'Client photos',
                'sort_order' => 6,
            ],

            [
                'name' => 'Transformations',
                'slug' => 'transformations',
                'icon' => 'heroicon-o-sparkles',
                'description' => 'Before and after images',
                'sort_order' => 7,
            ],

            [
                'name' => 'Settings',
                'slug' => 'settings',
                'icon' => 'heroicon-o-cog-6-tooth',
                'description' => 'Website assets',
                'sort_order' => 8,
            ],

            [
                'name' => 'General',
                'slug' => 'general',
                'icon' => 'heroicon-o-folder',
                'description' => 'General media',
                'sort_order' => 9,
            ],

        ];

        foreach ($categories as $category) {

            MediaCategory::updateOrCreate(

                ['slug' => $category['slug']],

                $category + [
                    'active' => true,
                ]

            );

        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsiteSection;

class WebsiteSectionSeeder extends Seeder
{
    public function run(): void
    {
        WebsiteSection::truncate();

        $sections = [

            [
                'title' => 'Hero',
                'page' => 'home',
                'section' => 'hero',
                'sort_order' => 1,
            ],

            [
                'title' => 'Homepage Cards',
                'page' => 'home',
                'section' => 'homepage_cards',
                'sort_order' => 2,
            ],

            [
                'title' => 'Programs',
                'page' => 'home',
                'section' => 'programs',
                'sort_order' => 3,
            ],

            [
                'title' => 'Products',
                'page' => 'home',
                'section' => 'products',
                'sort_order' => 4,
            ],

            [
                'title' => 'Transformations',
                'page' => 'home',
                'section' => 'transformations',
                'sort_order' => 5,
            ],

            [
                'title' => 'Testimonials',
                'page' => 'home',
                'section' => 'testimonials',
                'sort_order' => 6,
            ],

            [
                'title' => 'Blogs',
                'page' => 'home',
                'section' => 'blogs',
                'sort_order' => 7,
            ],

            [
                'title' => 'Contact',
                'page' => 'home',
                'section' => 'contact',
                'sort_order' => 8,
            ],

        ];

        foreach ($sections as $section) {

            WebsiteSection::create([

                ...$section,

                'enabled' => true,

                'template' => 'default',

                'settings' => [],

            ]);

        }
    }
}
<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Exercise;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FitnessHubContentDiversificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness & Nutrition',
            'email' => 'coach@dksinghfitness.com',
            'contact_title' => 'Get in Touch',
        ]);
    }

    public function test_fitness_hub_returns_http_200(): void
    {
        $response = $this->get('/fitness');
        $response->assertStatus(200);
    }

    public function test_blog_returns_http_200(): void
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
    }

    public function test_overlapping_articles_are_absent_from_fitness_preview_cards(): void
    {
        $category = BlogCategory::create([
            'name' => 'Workout & Training',
            'slug' => 'workout-and-training',
            'status' => true,
        ]);

        // Create the 5 overlapping article IDs
        $overlappingIds = [119, 162, 287, 299, 181];
        foreach ($overlappingIds as $id) {
            BlogPost::create([
                'id' => $id,
                'blog_category_id' => $category->id,
                'title' => "Overlapping Article {$id}",
                'slug' => "overlapping-article-{$id}",
                'excerpt' => "Excerpt for overlapping article {$id}",
                'content' => "<p>Content for {$id}</p>",
                'author' => 'DK Singh',
                'reading_time' => 5,
                'status' => true,
                'featured' => true,
                'published_at' => now(),
            ]);
        }

        // Create replacement / alternative articles
        $replacements = [107, 318, 115, 126, 175];
        foreach ($replacements as $id) {
            BlogPost::create([
                'id' => $id,
                'blog_category_id' => $category->id,
                'title' => "Replacement Article {$id}",
                'slug' => "replacement-article-{$id}",
                'excerpt' => "Excerpt for replacement article {$id}",
                'content' => "<p>Content for {$id}</p>",
                'author' => 'DK Singh',
                'reading_time' => 5,
                'status' => true,
                'featured' => true,
                'published_at' => now()->subDay(),
            ]);
        }

        $response = $this->get('/fitness');
        $response->assertStatus(200);

        $fitnessViewData = $response->original->getData();
        $renderedIds = [$fitnessViewData['featuredPost']->id];
        foreach ($fitnessViewData['sections'] as $sec) {
            foreach ($sec['posts'] as $p) {
                $renderedIds[] = $p->id;
            }
        }

        foreach ($overlappingIds as $excludedId) {
            $this->assertNotContains(
                $excludedId,
                $renderedIds,
                "Article ID {$excludedId} must NOT appear in /fitness preview cards."
            );
        }
    }

    public function test_overlapping_articles_remain_accessible_and_published_on_blog(): void
    {
        $category = BlogCategory::create([
            'name' => 'Indian Diet',
            'slug' => 'indian-diet',
            'status' => true,
        ]);

        $overlappingPost = BlogPost::create([
            'id' => 119,
            'blog_category_id' => $category->id,
            'title' => 'How to Choose the Right Workout Split Based on Your Lifestyle',
            'slug' => 'how-to-choose-the-right-workout-split-based-on-your-lifestyle',
            'excerpt' => 'Workout split guide',
            'content' => '<p>Article body here</p>',
            'author' => 'DK Singh',
            'status' => true,
            'featured' => true,
            'published_at' => now(),
        ]);

        // Direct article URL must be 200
        $response = $this->get('/blog/' . $overlappingPost->slug);
        $response->assertStatus(200);
        $response->assertSee($overlappingPost->title);

        // Blog index must be 200 and include the post
        $blogResponse = $this->get('/blog');
        $blogResponse->assertStatus(200);
        $blogResponse->assertSee($overlappingPost->title);
    }

    public function test_products_section_remains_untouched_and_functional(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    public function test_seven_approved_content_quality_recommendations_render_accurately(): void
    {
        $category = BlogCategory::create([
            'name' => 'Fitness & Training',
            'slug' => 'workout-and-training',
            'status' => true,
        ]);

        $articles = [
            107 => 'How to Build Muscle on an Indian Vegetarian Diet',
            106 => 'Walking vs Jogging vs Running for Weight Loss: Which One Burns More Fat?',
            109 => 'How Many Steps a Day to Lose Weight?',
            169 => 'HIIT vs Steady-State Cardio',
            318 => '7 Back Exercises for Strength & Muscle Gain',
            337 => 'Breaking The ‘I Workout So I Can Enjoy Life’ Myth',
            346 => 'Weight Training Exercises',
            336 => 'How Many Days Should You Strength Train in a Week?',
            118 => 'Best Pre-Workout Meal for Indian Vegetarians',
            154 => 'Senior Fitness Simplified: Gentle Workouts That Boost Mobility and Confidence',
            167 => 'Yoga for Stress Relief',
            128 => 'Top 10 Yoga Asanas',
            153 => 'Digital Detox Guide',
            125 => 'High Blood Pressure Causes',
            259 => 'Detox Water for Weight Loss',
            328 => '“SLEEP” A neglected tool in a fatloss',
            274 => 'How to Make Healthy Eating a Sustainable Habit: A Guide',
            179 => 'How to Maintain Normal Sugar Levels Naturally',
            115 => 'The Science Behind Calorie Tracking',
            126 => 'How Much Protein is in 100g Paneer?',
            175 => 'Is Makhana the New Superfood Snack',
        ];

        foreach ($articles as $id => $title) {
            BlogPost::forceCreate([
                'id' => $id,
                'blog_category_id' => $category->id,
                'title' => $title,
                'slug' => \Illuminate\Support\Str::slug($title) . "-{$id}",
                'excerpt' => "Excerpt for {$title}",
                'content' => "<p>Content for {$title}</p>",
                'author' => 'DK Singh',
                'reading_time' => 5,
                'status' => true,
                'featured' => ($id === 107),
                'published_at' => now(),
            ]);
        }

        $response = $this->get('/fitness');
        $response->assertStatus(200);

        $viewData = $response->original->getData();

        // 1. Hero verification: ID 107
        $this->assertEquals(107, $viewData['featuredPost']->id, 'Hero spotlight must be ID 107');

        $sectionsBySlug = collect($viewData['sections'])->keyBy('slug');

        // 2. Change #1: Cardio #1 is ID 106
        $cardioPosts = $sectionsBySlug['cardio']['posts'];
        $this->assertEquals(106, $cardioPosts[0]->id, 'Cardio #1 must be ID 106');

        // 3. Change #2: Exercise #3 is ID 346
        $exercisePosts = $sectionsBySlug['exercise']['posts'];
        $this->assertEquals(346, $exercisePosts[2]->id, 'Exercise #3 must be ID 346');

        // 4. Change #3: Strength #2 is ID 336
        $strengthPosts = $sectionsBySlug['strength-training']['posts'];
        $this->assertEquals(336, $strengthPosts[1]->id, 'Strength #2 must be ID 336');

        // 5. Change #4: Strength #3 is ID 118
        $this->assertEquals(118, $strengthPosts[2]->id, 'Strength #3 must be ID 118');

        // 6. Change #5: Yoga #1 is ID 154
        $yogaPosts = $sectionsBySlug['yoga']['posts'];
        $this->assertEquals(154, $yogaPosts[0]->id, 'Yoga #1 must be ID 154');

        // 7. Change #6: Wellness #1 is ID 328
        $wellnessPosts = $sectionsBySlug['wellness']['posts'];
        $this->assertEquals(328, $wellnessPosts[0]->id, 'Wellness #1 must be ID 328');

        // 8. Change #7: Wellness #2 is ID 274
        $this->assertEquals(274, $wellnessPosts[1]->id, 'Wellness #2 must be ID 274');

        // Holistic contains ID 153 (preserved as requested)
        $holisticPosts = $sectionsBySlug['holistic-fitness']['posts'];
        $this->assertTrue(
            $holisticPosts->contains('id', 153),
            'Holistic Fitness section must retain ID 153'
        );
    }
}

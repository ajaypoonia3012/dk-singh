<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditFixesRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'email' => 'coach@dksinghfitness.com',
            'contact_title' => 'Get in Touch',
        ]);
    }

    public function test_phpinfo_file_does_not_exist_in_public_directory(): void
    {
        $this->assertFileDoesNotExist(public_path('phpinfo.php'));
    }

    public function test_sitemap_returns_valid_xml_with_proper_route_names(): void
    {
        $category = BlogCategory::query()->create([
            'name' => 'Fitness',
            'slug' => 'fitness',
            'status' => true,
        ]);

        $author = User::factory()->create();

        BlogPost::query()->create([
            'title' => 'Sample Post',
            'slug' => 'sample-post',
            'content' => 'Content here',
            'excerpt' => 'Excerpt',
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('content-type'));
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString('/blog/sample-post', $response->getContent());
        $this->assertStringContainsString('/plans', $response->getContent());
        $this->assertStringContainsString('/fitness-hub', $response->getContent());
    }

    public function test_blog_search_filters_articles_by_keyword(): void
    {
        $category = BlogCategory::query()->create([
            'name' => 'Workout Tips',
            'slug' => 'workout-tips',
            'status' => true,
        ]);

        $author = User::factory()->create();

        BlogPost::query()->create([
            'title' => 'Kettlebell Swing Guide',
            'slug' => 'kettlebell-swing-guide',
            'content' => 'Comprehensive tutorial on kettlebell swings for core strength.',
            'excerpt' => 'Learn how to do kettlebells.',
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => true,
            'published_at' => now(),
        ]);

        BlogPost::query()->create([
            'title' => 'Vegan High Protein Diet',
            'slug' => 'vegan-high-protein-diet',
            'content' => 'Plant-based meal plans for muscle building.',
            'excerpt' => 'High protein vegan meal guide.',
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog?search=kettlebell');

        $response->assertStatus(200);
        $posts = $response->viewData('posts');
        $this->assertTrue($posts->contains('title', 'Kettlebell Swing Guide'));
        $this->assertFalse($posts->contains('title', 'Vegan High Protein Diet'));
    }

    public function test_blog_post_detail_renders_without_sidebar_categories_error(): void
    {
        $category = BlogCategory::query()->create([
            'name' => 'Nutrition',
            'slug' => 'nutrition',
            'status' => true,
        ]);

        $author = User::factory()->create();

        $post = BlogPost::query()->create([
            'title' => 'Macro Counting 101',
            'slug' => 'macro-counting-101',
            'content' => 'Understanding protein, fats, and carbohydrates.',
            'excerpt' => 'Intro to macros.',
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/macro-counting-101');

        $response->assertStatus(200);
        $response->assertSee('Macro Counting 101');
        $response->assertSee('Nutrition');
    }

    public function test_blog_category_page_renders_with_sidebar(): void
    {
        $category = BlogCategory::query()->create([
            'name' => 'Strength',
            'slug' => 'strength',
            'status' => true,
        ]);

        $author = User::factory()->create();

        BlogPost::query()->create([
            'title' => 'Deadlift Technique',
            'slug' => 'deadlift-technique',
            'content' => 'How to deadlift safely.',
            'excerpt' => 'Deadlift tips.',
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/category/strength');

        $response->assertStatus(200);
        $response->assertSee('Deadlift Technique');
    }

    public function test_blog_tag_page_renders_with_sidebar(): void
    {
        $category = BlogCategory::query()->create([
            'name' => 'Cardio',
            'slug' => 'cardio',
            'status' => true,
        ]);

        $tag = BlogTag::query()->create([
            'name' => 'HIIT',
            'slug' => 'hiit',
        ]);

        $author = User::factory()->create();

        $post = BlogPost::query()->create([
            'title' => 'HIIT for Fat Loss',
            'slug' => 'hiit-for-fat-loss',
            'content' => 'High intensity interval training.',
            'excerpt' => 'HIIT tips.',
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $post->tags()->attach($tag);

        $response = $this->get('/blog/tag/hiit');

        $response->assertStatus(200);
        $response->assertSee('HIIT for Fat Loss');
    }

    public function test_contact_form_submits_and_stores_lead_in_database(): void
    {
        $payload = [
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
            'phone' => '9876543210',
            'message' => 'I would like to inquire about personal coaching.',
        ];

        $response = $this->post('/contact', $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_leads', [
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
            'phone' => '9876543210',
        ]);
    }
}

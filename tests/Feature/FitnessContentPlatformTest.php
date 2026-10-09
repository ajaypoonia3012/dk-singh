<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Exercise;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FitnessContentPlatformTest extends TestCase
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

    protected function createDummyExercise(array $overrides = []): Exercise
    {
        return Exercise::create(array_merge([
            'name' => 'Push-Ups',
            'slug' => 'push-ups',
            'short_description' => 'A foundational upper-body calisthenic pressing exercise.',
            'exercise_category' => 'Chest',
            'primary_muscle' => 'Pectoralis Major',
            'secondary_muscles' => ['Triceps Brachii', 'Anterior Deltoids'],
            'equipment' => 'Bodyweight',
            'difficulty' => 'Beginner',
            'movement_pattern' => 'Horizontal Push',
            'setup' => 'Place palms flat on the floor shoulder-width apart.',
            'execution_steps' => [
                'Inhale as you lower your chest towards the floor.',
                'Pause briefly with elbows at 45 degrees.',
                'Exhale forcefully and press through palms back to lockout.',
            ],
            'breathing_guidance' => 'Inhale on the descent; exhale during the pressing concentric phase.',
            'common_mistakes' => [
                'Flaring elbows out at 90 degrees.',
                'Sagging lower back and hips.',
            ],
            'safety_considerations' => 'Maintain active glute and core tension to protect lumbar spine.',
            'beginner_modification' => 'Elevate hands on a bench or sturdy counter.',
            'advanced_variation' => 'Place feet on an elevated bench or wear a weighted vest.',
            'home_variation' => 'Loop a resistance band across your upper back.',
            'gym_variation' => 'Parallel bar bodyweight dips or dumbbell press.',
            'faqs' => [
                [
                    'question' => 'How many push-ups should a beginner aim for?',
                    'answer' => 'Start with 3 sets of 6 to 10 strict repetitions.',
                ],
            ],
            'seo_title' => 'Push-Ups Technique & Form Guide | DK Singh Fitness',
            'meta_description' => 'Master the push-up with DK Singh step-by-step cues.',
            'status' => true,
            'featured' => true,
        ], $overrides));
    }

    protected function createDummyBlogPost(array $overrides = []): BlogPost
    {
        $category = BlogCategory::firstOrCreate(
            ['slug' => 'workout-and-training'],
            ['name' => 'Workout & Training', 'status' => true]
        );

        return BlogPost::create(array_merge([
            'blog_category_id' => $category->id,
            'title' => 'The Ultimate Guide to Progressive Overload',
            'slug' => 'the-ultimate-guide-to-progressive-overload',
            'excerpt' => 'How to systematically increase stimulus for muscle growth.',
            'content' => '<h2>Understanding Progressive Overload</h2><p>Lifting heavier or doing more reps systematically forces adaptation.</p><h3>Tracking Weekly Volume</h3><p>Log your sets and reps accurately each session.</p>',
            'author' => 'DK Singh',
            'reading_time' => 7,
            'views' => 250,
            'content_type' => 'workout_guide',
            'featured' => true,
            'status' => true,
            'published_at' => now(),
            'seo_title' => 'Progressive Overload Blueprint | DK Singh Fitness',
            'seo_description' => 'Learn how to progressive overload your training safely.',
        ], $overrides));
    }

    public function test_fitness_hub_index_loads_successfully(): void
    {
        $this->createDummyBlogPost();
        $this->createDummyExercise();

        $response = $this->get('/fitness');

        $response->assertStatus(200);
        $response->assertSee('DK Singh Fitness Knowledge Platform');
        $response->assertSee('Browse by Pillar');
        $response->assertSee('Exercise Library');
        $response->assertSee('Cardio & Conditioning');
        $response->assertSee('Strength Training');
        $response->assertSee('Yoga & Mobility');
        $response->assertSee('Holistic Fitness');
        $response->assertDontSee('Equipment & Products');
        $response->assertDontSee('Healthline');
        $response->assertDontSee('Alpha Coach');
        $response->assertDontSee('adsbygoogle');
    }

    public function test_fitness_exercise_hub_loads(): void
    {
        $this->createDummyBlogPost();
        $this->createDummyExercise();

        $response = $this->get('/fitness/exercise');

        $response->assertStatus(200);
        $response->assertSee('Exercise Techniques', false);
        $response->assertSee('Browse Exercise Library', false);
        $response->assertDontSee('Healthline');
    }

    public function test_fitness_cardio_hub_loads(): void
    {
        $this->createDummyBlogPost(['title' => 'Walking For Fat Loss and Heart Health']);
        $this->createDummyExercise(['exercise_category' => 'Cardio', 'slug' => 'brisk-walking']);

        $response = $this->get('/fitness/cardio');

        $response->assertStatus(200);
        $response->assertSee('Cardio, Walking', false);
        $response->assertSee('Brisk Walking for Fat Loss');
        $response->assertDontSee('Healthline');
    }

    public function test_fitness_products_is_permanently_redirected_to_fitness_hub(): void
    {
        $response = $this->get('/fitness/products');

        $response->assertStatus(301);
        $response->assertRedirect('/fitness');
    }

    public function test_fitness_strength_training_hub_loads(): void
    {
        $this->createDummyBlogPost();
        $this->createDummyExercise();

        $response = $this->get('/fitness/strength-training');

        $response->assertStatus(200);
        $response->assertSee('Strength Training', false);
        $response->assertSee('Muscle Building Protocols', false);
        $response->assertSee('Popular Training Splits Compared');
        $response->assertSee('Push / Pull / Legs (PPL)');
        $response->assertDontSee('Healthline');
    }

    public function test_fitness_yoga_hub_loads(): void
    {
        $this->createDummyBlogPost([
            'title' => 'Foundational Yoga Stretches for Tight Lifters',
        ]);
        $this->createDummyExercise([
            'name' => 'Cat-Cow Spinal Stretch',
            'slug' => 'cat-cow-spinal-stretch',
            'exercise_category' => 'Yoga & Flexibility',
        ]);

        $response = $this->get('/fitness/yoga');

        $response->assertStatus(200);
        $response->assertSee('Yoga, Mobility', false);
        $response->assertSee('Functional Flexibility', false);
        $response->assertSee('Educational Guidance Notice');
        $response->assertDontSee('Healthline');
    }

    public function test_fitness_holistic_fitness_hub_loads(): void
    {
        $this->createDummyBlogPost([
            'title' => 'How Sleep and Stress Affect Fat Loss',
        ]);

        $response = $this->get('/fitness/holistic-fitness');

        $response->assertStatus(200);
        $response->assertSee('Holistic Fitness, Recovery', false);
        $response->assertSee('Daily Habits', false);
        $response->assertSee('The 4 Pillars of Sustainable Health');
        $response->assertDontSee('Healthline');
    }

    public function test_exercise_library_index_and_filtering(): void
    {
        $ex1 = $this->createDummyExercise([
            'name' => 'Barbell Squat',
            'slug' => 'barbell-squat',
            'exercise_category' => 'Legs',
            'primary_muscle' => 'Quadriceps',
            'equipment' => 'Barbell',
            'difficulty' => 'Intermediate',
        ]);

        $ex2 = $this->createDummyExercise([
            'name' => 'Push-Ups',
            'slug' => 'push-ups',
            'exercise_category' => 'Chest',
            'primary_muscle' => 'Pectoralis Major',
            'equipment' => 'Bodyweight',
            'difficulty' => 'Beginner',
        ]);

        // General list
        $response = $this->get('/fitness/exercise-library');
        $response->assertStatus(200);
        $response->assertSee('Barbell Squat');
        $response->assertSee('Push-Ups');

        // Filter by category
        $filteredResponse = $this->get('/fitness/exercise-library?category=Chest');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Push-Ups');
        $filteredResponse->assertDontSee('Barbell Squat');

        // Filter with search term
        $searchResponse = $this->get('/fitness/exercise-library?search=Squat');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Barbell Squat');
        $searchResponse->assertDontSee('Push-Ups');
    }

    public function test_exercise_detail_page_renders_with_schema_and_cues(): void
    {
        $exercise = $this->createDummyExercise([
            'name' => 'Barbell Deadlift',
            'slug' => 'barbell-deadlift',
            'primary_muscle' => 'Hamstrings',
            'equipment' => 'Barbell',
            'difficulty' => 'Advanced',
            'movement_pattern' => 'Hip Hinge',
        ]);

        $response = $this->get("/fitness/exercise-library/{$exercise->slug}");

        $response->assertStatus(200);
        $response->assertSee('Barbell Deadlift');
        $response->assertSee('Step-by-Step Execution');
        $response->assertSee('Breathing', false);
        $response->assertSee('Intra-Abdominal Pressure', false);
        $response->assertSee('Common Mistakes to Avoid');
        $response->assertSee('schema.org');
        $response->assertSee('HowTo', false);
        $response->assertSee('Join 1-on-1 Coaching');
        $response->assertDontSee('Healthline');
    }

    public function test_unified_search_returns_articles_and_exercises(): void
    {
        $this->createDummyBlogPost([
            'title' => 'How to Master the Bench Press',
            'slug' => 'how-to-master-the-bench-press',
        ]);

        $this->createDummyExercise([
            'name' => 'Dumbbell Bench Press',
            'slug' => 'dumbbell-bench-press',
        ]);

        $response = $this->get('/fitness/search?q=Bench');

        $response->assertStatus(200);
        $response->assertSee('Results for');
        $response->assertSee('How to Master the Bench Press');
        $response->assertSee('Dumbbell Bench Press');
    }

    public function test_blog_post_detail_renders_table_of_contents_and_disclaimer(): void
    {
        $post = $this->createDummyBlogPost([
            'title' => 'Comprehensive Guide to Building Chest Hypertrophy',
            'slug' => 'comprehensive-guide-to-building-chest-hypertrophy',
            'content' => '<h2>Anatomy of the Pectoral Complex</h2><p>Upper, middle, and lower heads.</p><h2>Optimal Weekly Training Volume</h2><p>12 to 18 weekly direct sets.</p>',
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200);
        $response->assertSee('Table of Contents');
        $response->assertSee('Anatomy of the Pectoral Complex');
        $response->assertSee('Optimal Weekly Training Volume');
        $response->assertSee('Educational Health', false);
        $response->assertSee('Fitness Notice', false);
        $response->assertSee('DK Singh Coaching');
    }

    public function test_sitemap_contains_new_fitness_and_exercise_urls(): void
    {
        $exercise = $this->createDummyExercise([
            'name' => 'Romanian Deadlift',
            'slug' => 'romanian-deadlift',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee(url('/fitness'));
        $response->assertSee(url('/fitness/exercise'));
        $response->assertSee(url('/fitness/cardio'));
        $response->assertDontSee(url('/fitness/products'));
        $response->assertSee(url('/fitness/strength-training'));
        $response->assertSee(url('/fitness/yoga'));
        $response->assertSee(url('/fitness/holistic-fitness'));
        $response->assertSee(url('/fitness/exercise-library'));
        $response->assertSee(url("/fitness/exercise-library/{$exercise->slug}"));
    }

    public function test_zero_advertisement_verification_on_fitness_views(): void
    {
        $this->createDummyExercise();
        $this->createDummyBlogPost();

        $routesToTest = [
            '/fitness',
            '/fitness/exercise',
            '/fitness/cardio',
            '/fitness/strength-training',
            '/fitness/yoga',
            '/fitness/holistic-fitness',
            '/fitness/exercise-library',
        ];

        foreach ($routesToTest as $uri) {
            $resp = $this->get($uri);
            $resp->assertStatus(200);
            $resp->assertDontSee('adsbygoogle');
            $resp->assertDontSee('googlesyndication');
            $resp->assertDontSee('doubleclick');
            $resp->assertDontSee('ad-slot');
            $resp->assertDontSee('adservice');
        }
    }
}

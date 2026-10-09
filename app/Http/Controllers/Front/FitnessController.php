<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Exercise;
use Illuminate\Http\Request;

class FitnessController extends Controller
{
    /**
     * Main Fitness Editorial Hub (/fitness)
     */
    public function index()
    {
        // 5 overlapping articles identified in audit to exclude from /fitness preview cards to prevent overlap with /blog page 1
        $excludedOverlappingIds = [119, 162, 287, 299, 181];

        // Editorial Spotlight: Flagship muscle & Indian nutrition guide (ID 107 replaces overlapping ID 119)
        $featuredPost = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->whereNotIn('id', $excludedOverlappingIds)
            ->where('id', 107)
            ->first()
            ?? BlogPost::with(['category', 'media'])
                ->where('status', true)
                ->where('featured', true)
                ->whereNotIn('id', $excludedOverlappingIds)
                ->latest('published_at')
                ->first();

        $featuredPosts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->whereNotIn('id', $excludedOverlappingIds)
            ->when($featuredPost, fn($q) => $q->where('id', '!=', $featuredPost->id))
            ->latest('published_at')
            ->take(6)
            ->get();

        $featuredExercises = Exercise::with('media')
            ->where('status', true)
            ->where('featured', true)
            ->take(6)
            ->get();

        // Topic sections preview
        $sections = [
            [
                'title' => 'Exercise',
                'slug' => 'exercise',
                'url' => route('fitness.exercise'),
                'description' => 'Movement mechanics, exercise tutorials, and muscle targeting guides.',
                'posts' => (function() use ($excludedOverlappingIds) {
                    $curated = BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereIn('id', [318, 337, 346])
                        ->get();

                    if ($curated->count() >= 3) {
                        return $curated->sortBy(fn($p) => array_search($p->id, [318, 337, 346]))->values();
                    }

                    return BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereIn('content_type', ['exercise_guide', 'workout_guide'])
                        ->latest('published_at')->take(3)->get();
                })(),
            ],
            [
                'title' => 'Strength Training',
                'slug' => 'strength-training',
                'url' => route('fitness.strength-training'),
                'description' => 'Hypertrophy principles, progressive overload, and structured workout splits.',
                'posts' => (function() use ($excludedOverlappingIds) {
                    $curated = BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereIn('id', [318, 336, 118])
                        ->get();

                    if ($curated->count() >= 3) {
                        return $curated->sortBy(fn($p) => array_search($p->id, [318, 336, 118]))->values();
                    }

                    return BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->where(function($q) {
                            $q->whereHas('category', fn($c) => $c->where('slug', 'workout-and-training'))
                              ->orWhere('title', 'like', '%workout%')
                              ->orWhere('title', 'like', '%muscle%')
                              ->orWhere('title', 'like', '%split%');
                        })->latest('published_at')->take(3)->get();
                })(),
            ],
            [
                'title' => 'Cardio',
                'slug' => 'cardio',
                'url' => route('fitness.cardio'),
                'description' => 'Brisk walking, running, HIIT, conditioning, and fat loss energy expenditure.',
                'posts' => (function() use ($excludedOverlappingIds) {
                    $curated = BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereIn('id', [106, 109, 169])
                        ->get();

                    if ($curated->count() >= 3) {
                        return $curated->sortBy(fn($p) => array_search($p->id, [106, 109, 169]))->values();
                    }

                    return BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->where(function($q) {
                            $q->where('title', 'like', '%walking%')
                              ->orWhere('title', 'like', '%running%')
                              ->orWhere('title', 'like', '%jogging%')
                              ->orWhere('title', 'like', '%cardio%')
                              ->orWhere('title', 'like', '%steps%')
                              ->orWhere('title', 'like', '%hiit%');
                        })->latest('published_at')->take(3)->get();
                })(),
            ],
            [
                'title' => 'Yoga & Flexibility',
                'slug' => 'yoga',
                'url' => route('fitness.yoga'),
                'description' => 'Spinal mobility, recovery asanas, flexibility routines, and mindful breathing.',
                'posts' => (function() use ($excludedOverlappingIds) {
                    $curated = BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereIn('id', [154, 167, 128])
                        ->get();

                    if ($curated->count() >= 3) {
                        return $curated->sortBy(fn($p) => array_search($p->id, [154, 167, 128]))->values();
                    }

                    return BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->where(function($q) {
                            $q->where('title', 'like', '%yoga%')
                              ->orWhere('title', 'like', '%asanas%')
                              ->orWhere('title', 'like', '%stretch%')
                              ->orWhere('title', 'like', '%stress%');
                        })->latest('published_at')->take(3)->get();
                })(),
            ],
            [
                'title' => 'Holistic Fitness',
                'slug' => 'holistic-fitness',
                'url' => route('fitness.holistic-fitness'),
                'description' => 'Restorative sleep, metabolic health, daily habits, and stress resilience.',
                'posts' => BlogPost::with(['category', 'media'])->where('status', true)
                    ->whereNotIn('id', $excludedOverlappingIds)
                    ->where(function($q) {
                        $q->whereHas('category', fn($c) => $c->where('slug', 'lifestyle-and-wellness'))
                          ->orWhere('title', 'like', '%metabolism%')
                          ->orWhere('title', 'like', '%sleep%')
                          ->orWhere('title', 'like', '%blood pressure%')
                          ->orWhere('title', 'like', '%detox%');
                    })->latest('published_at')->take(3)->get(),
            ],
            [
                'title' => 'Wellness',
                'slug' => 'wellness',
                'url' => route('fitness.wellness'),
                'description' => 'Mindfulness, sleep hygiene, stress resilience, sustainable habits, and metabolic longevity.',
                'posts' => (function() use ($excludedOverlappingIds) {
                    $curated = BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereIn('id', [328, 274, 179])
                        ->get();

                    if ($curated->count() >= 3) {
                        return $curated->sortBy(fn($p) => array_search($p->id, [328, 274, 179]))->values();
                    }

                    return BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->where(function($q) {
                            $q->whereHas('category', fn($c) => $c->whereIn('slug', ['lifestyle-and-wellness', 'mindset-and-motivation']))
                              ->orWhere('title', 'like', '%sleep%')
                              ->orWhere('title', 'like', '%stress%')
                              ->orWhere('title', 'like', '%habit%')
                              ->orWhere('title', 'like', '%mental%');
                        })->latest('published_at')->take(3)->get();
                })(),
            ],
            [
                'title' => 'Nutrition & Healthy Diets',
                'slug' => 'nutrition',
                'url' => route('blog.category', 'nutrition'),
                'description' => 'Indian vegetarian meal planning, macronutrient balance, fat loss nutrition, and recipe guides.',
                'posts' => (function() use ($excludedOverlappingIds) {
                    $curated = BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereIn('id', [115, 126, 175])
                        ->get();

                    if ($curated->count() >= 3) {
                        return $curated->sortBy(fn($p) => array_search($p->id, [115, 126, 175]))->values();
                    }

                    return BlogPost::with(['category', 'media'])->where('status', true)
                        ->whereNotIn('id', $excludedOverlappingIds)
                        ->whereHas('category', fn($c) => $c->whereIn('slug', ['nutrition', 'indian-diet', 'healthy-recipes', 'weight-loss']))
                        ->latest('published_at')->take(3)->get();
                })(),
            ],
        ];

        $totalArticles = BlogPost::where('status', true)->count();
        $totalExercises = Exercise::where('status', true)->count();

        return view('fitness.index', compact(
            'featuredPost',
            'featuredPosts',
            'featuredExercises',
            'sections',
            'totalArticles',
            'totalExercises'
        ));
    }

    /**
     * Exercise Editorial Hub (/fitness/exercise)
     */
    public function exercise(Request $request)
    {
        $posts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->whereIn('content_type', ['exercise_guide', 'workout_guide'])
                  ->orWhereHas('category', fn($c) => $c->whereIn('slug', ['workout-and-training', 'home-workouts', 'fitness']));
            })
            ->latest('published_at')
            ->paginate(9);

        $exercises = Exercise::with('media')
            ->where('status', true)
            ->take(6)
            ->get();

        $subgroups = [
            ['title' => 'Full Body', 'filter' => 'Full Body'],
            ['title' => 'Upper Body', 'filter' => 'Chest'],
            ['title' => 'Lower Body', 'filter' => 'Legs'],
            ['title' => 'Core Strength', 'filter' => 'Core'],
            ['title' => 'Mobility & Posture', 'filter' => 'Mobility'],
            ['title' => 'Bodyweight & Home', 'filter' => 'Bodyweight'],
        ];

        return view('fitness.exercise', compact('posts', 'exercises', 'subgroups'));
    }

    /**
     * Cardio Content Hub (/fitness/cardio)
     */
    public function cardio(Request $request)
    {
        $posts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%walking%')
                  ->orWhere('title', 'like', '%running%')
                  ->orWhere('title', 'like', '%jogging%')
                  ->orWhere('title', 'like', '%cardio%')
                  ->orWhere('title', 'like', '%steps%')
                  ->orWhere('title', 'like', '%hiit%')
                  ->orWhere('title', 'like', '%heart%');
            })
            ->latest('published_at')
            ->paginate(9);

        $exercises = Exercise::with('media')
            ->where('status', true)
            ->where(function ($q) {
                $q->where('exercise_category', 'Cardio')
                  ->orWhere('movement_pattern', 'Conditioning');
            })
            ->take(4)
            ->get();

        $subtopics = [
            'Brisk Walking for Fat Loss',
            'Running vs Jogging',
            'High-Intensity Interval Training (HIIT)',
            'Low-Impact Joint-Friendly Cardio',
            'Cardiovascular Heart Health',
            'Daily Step Count Optimization',
        ];

        return view('fitness.cardio', compact('posts', 'exercises', 'subtopics'));
    }

    /**
     * Strength Training Hub (/fitness/strength-training)
     */
    public function strengthTraining(Request $request)
    {
        $posts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->whereHas('category', fn($c) => $c->where('slug', 'workout-and-training'))
                  ->orWhere('title', 'like', '%workout%')
                  ->orWhere('title', 'like', '%split%')
                  ->orWhere('title', 'like', '%muscle%')
                  ->orWhere('title', 'like', '%strength%')
                  ->orWhere('title', 'like', '%progressive overload%');
            })
            ->latest('published_at')
            ->paginate(9);

        $exercises = Exercise::with('media')
            ->where('status', true)
            ->whereIn('exercise_category', ['Chest', 'Back', 'Legs', 'Shoulders'])
            ->take(4)
            ->get();

        $pillars = [
            'Hypertrophy & Muscle Building Science',
            'Progressive Overload Fundamentals',
            'Push / Pull / Legs (PPL) Split',
            'Upper / Lower Body Split',
            'Training Volume & Frequency Guidelines',
            'Post-Workout Recovery & Sleep',
        ];

        return view('fitness.strength-training', compact('posts', 'exercises', 'pillars'));
    }

    /**
     * Yoga & Flexibility Hub (/fitness/yoga)
     */
    public function yoga(Request $request)
    {
        $posts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%yoga%')
                  ->orWhere('title', 'like', '%asanas%')
                  ->orWhere('title', 'like', '%stretch%')
                  ->orWhere('title', 'like', '%flexibility%')
                  ->orWhere('title', 'like', '%stress%');
            })
            ->latest('published_at')
            ->paginate(9);

        $exercises = Exercise::with('media')
            ->where('status', true)
            ->whereIn('exercise_category', ['Yoga & Flexibility', 'Mobility'])
            ->take(4)
            ->get();

        $disciplines = [
            'Foundational Asanas for Beginners',
            'Spinal Mobility & Desk Fatigue Relief',
            'Yoga for Athletic Recovery',
            'Stress Management & Pranayama Breathing',
            'Hamstring & Hip Mobility Routines',
        ];

        return view('fitness.yoga', compact('posts', 'exercises', 'disciplines'));
    }

    /**
     * Holistic Fitness & Lifestyle Hub (/fitness/holistic-fitness)
     */
    public function holisticFitness(Request $request)
    {
        $posts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->whereHas('category', fn($c) => $c->where('slug', 'lifestyle-and-wellness'))
                  ->orWhere('title', 'like', '%metabolism%')
                  ->orWhere('title', 'like', '%sleep%')
                  ->orWhere('title', 'like', '%habits%')
                  ->orWhere('title', 'like', '%stress%')
                  ->orWhere('title', 'like', '%blood pressure%')
                  ->orWhere('title', 'like', '%detox%')
                  ->orWhere('title', 'like', '%wellness%');
            })
            ->latest('published_at')
            ->paginate(9);

        $exercises = Exercise::with('media')
            ->where('status', true)
            ->whereIn('exercise_category', ['Mobility', 'Core'])
            ->take(4)
            ->get();

        $lifestyleHabits = [
            'Circadian Rhythm & Sleep Optimization',
            'Natural Blood Pressure Management',
            'Hydration & Electrolyte Science',
            'Daily Non-Exercise Activity (NEAT)',
            'Overcoming Workout Slumps & Burnout',
        ];

        return view('fitness.holistic-fitness', compact('posts', 'exercises', 'lifestyleHabits'));
    }

    /**
     * Wellness Editorial Hub (/fitness/wellness)
     */
    public function wellness(Request $request)
    {
        $featuredPost = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%mental health%')
                  ->orWhere('title', 'like', '%sleep%')
                  ->orWhere('title', 'like', '%digital detox%')
                  ->orWhere('title', 'like', '%metabolism%');
            })
            ->latest('published_at')
            ->first();

        // 1. Mind & Mental Well-Being
        $mindPosts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%mental%')
                  ->orWhere('title', 'like', '%digital detox%')
                  ->orWhere('title', 'like', '%mood%')
                  ->orWhere('title', 'like', '%consistency%')
                  ->orWhereHas('category', fn($c) => $c->where('slug', 'mindset-and-motivation'));
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        // 2. Sleep & Restorative Recovery
        $sleepPosts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%sleep%')
                  ->orWhere('title', 'like', '%recovery%')
                  ->orWhere('title', 'like', '%myalgia%')
                  ->orWhere('title', 'like', '%rest%');
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        // 3. Stress Management & Nervous System
        $stressPosts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%stress%')
                  ->orWhere('title', 'like', '%low impact%')
                  ->orWhere('title', 'like', '%burnout%');
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        // 4. Healthy Habits & Mindfulness
        $habitPosts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%habit%')
                  ->orWhere('title', 'like', '%mindful%')
                  ->orWhere('title', 'like', '%sustainable%');
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        // 5. Active Lifestyle & Longevity
        $lifestylePosts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%metabolism%')
                  ->orWhere('title', 'like', '%padel%')
                  ->orWhere('title', 'like', '%sugar%')
                  ->orWhere('title', 'like', '%detox%')
                  ->orWhereHas('category', fn($c) => $c->where('slug', 'lifestyle-and-wellness'));
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        // Restorative mobility exercises from Exercise Library
        $exercises = Exercise::with('media')
            ->where('status', true)
            ->where(function ($q) {
                $q->whereIn('exercise_category', ['Yoga & Flexibility', 'Mobility', 'Core'])
                  ->orWhere('movement_pattern', 'Mobility');
            })
            ->take(4)
            ->get();

        // All wellness articles paginated for browsing
        $allWellnessPosts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) {
                $q->whereHas('category', fn($c) => $c->whereIn('slug', ['lifestyle-and-wellness', 'mindset-and-motivation']))
                  ->orWhere('title', 'like', '%sleep%')
                  ->orWhere('title', 'like', '%stress%')
                  ->orWhere('title', 'like', '%mental%')
                  ->orWhere('title', 'like', '%habit%')
                  ->orWhere('title', 'like', '%mindful%')
                  ->orWhere('title', 'like', '%recovery%')
                  ->orWhere('title', 'like', '%metabolism%')
                  ->orWhere('title', 'like', '%detox%')
                  ->orWhere('title', 'like', '%sugar%');
            })
            ->latest('published_at')
            ->paginate(9);

        $wellnessPillars = [
            ['title' => 'Mind & Mental Well-Being', 'icon' => 'bi-brain', 'count' => $mindPosts->count(), 'anchor' => 'mental-wellbeing'],
            ['title' => 'Sleep & Recovery', 'icon' => 'bi-moon-stars-fill', 'count' => $sleepPosts->count(), 'anchor' => 'sleep-recovery'],
            ['title' => 'Stress Management', 'icon' => 'bi-heart-pulse-fill', 'count' => $stressPosts->count(), 'anchor' => 'stress-management'],
            ['title' => 'Healthy Habits', 'icon' => 'bi-calendar-check-fill', 'count' => $habitPosts->count(), 'anchor' => 'healthy-habits'],
            ['title' => 'Longevity & Vitality', 'icon' => 'bi-shield-check', 'count' => $lifestylePosts->count(), 'anchor' => 'longevity'],
        ];

        return view('fitness.wellness', compact(
            'featuredPost',
            'mindPosts',
            'sleepPosts',
            'stressPosts',
            'habitPosts',
            'lifestylePosts',
            'exercises',
            'allWellnessPosts',
            'wellnessPillars'
        ));
    }

    /**
     * Products route is retired from fitness platform: 301 permanent redirect to fitness hub
     */
    public function products(Request $request)
    {
        return redirect()->route('fitness.index', [], 301);
    }

    /**
     * Exercise Library Index with Search & Multi-Axis Filtering (/fitness/exercise-library)
     */
    public function exerciseLibrary(Request $request)
    {
        $filters = [
            'category' => $request->input('category'),
            'muscle' => $request->input('muscle'),
            'equipment' => $request->input('equipment'),
            'difficulty' => $request->input('difficulty'),
            'search' => $request->input('search'),
        ];

        $exercises = Exercise::with('media')
            ->published()
            ->filter($filters)
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Exercise::select('exercise_category')
            ->distinct()
            ->orderBy('exercise_category')
            ->pluck('exercise_category');

        $muscles = Exercise::select('primary_muscle')
            ->distinct()
            ->orderBy('primary_muscle')
            ->pluck('primary_muscle');

        $equipments = Exercise::select('equipment')
            ->distinct()
            ->orderBy('equipment')
            ->pluck('equipment');

        $difficulties = ['Beginner', 'Intermediate', 'Advanced'];

        return view('fitness.exercise-library.index', compact(
            'exercises',
            'filters',
            'categories',
            'muscles',
            'equipments',
            'difficulties'
        ));
    }

    /**
     * Individual Exercise Detail Reference Page (/fitness/exercise-library/{slug})
     */
    public function exerciseDetail(string $slug)
    {
        $exercise = Exercise::with('media')
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $exercise->increment('views');

        // Related exercises by category or movement pattern
        $relatedExercises = Exercise::with('media')
            ->where('id', '!=', $exercise->id)
            ->where('status', true)
            ->where(function ($q) use ($exercise) {
                $q->where('exercise_category', $exercise->exercise_category)
                  ->orWhere('primary_muscle', $exercise->primary_muscle);
            })
            ->take(3)
            ->get();

        // Related blog articles by muscle or category
        $relatedArticles = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where(function ($q) use ($exercise) {
                $q->where('title', 'like', "%{$exercise->exercise_category}%")
                  ->orWhere('content', 'like', "%{$exercise->primary_muscle}%")
                  ->orWhere('title', 'like', "%{$exercise->name}%");
            })
            ->take(3)
            ->get();

        return view('fitness.exercise-library.show', compact(
            'exercise',
            'relatedExercises',
            'relatedArticles'
        ));
    }

    /**
     * Unified Fitness Search (/fitness/search)
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));

        $articles = collect();
        $exercises = collect();

        if ($q !== '') {
            $terms = array_filter(preg_split('/[\s\-]+/', $q));

            $articles = BlogPost::with(['category', 'media'])
                ->where('status', true)
                ->where(function ($query) use ($q, $terms) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('excerpt', 'like', "%{$q}%")
                          ->orWhere('content', 'like', "%{$q}%");
                    foreach ($terms as $term) {
                        $query->orWhere('title', 'like', "%{$term}%");
                    }
                })
                ->take(12)
                ->get();

            $exercises = Exercise::with('media')
                ->where('status', true)
                ->filter(['q' => $q])
                ->take(8)
                ->get();
        }

        return view('fitness.search', compact('q', 'articles', 'exercises'));
    }
}

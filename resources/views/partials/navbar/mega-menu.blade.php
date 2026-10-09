<div
    id="fitnessMegaMenu"
    class="theme-mega-menu"
    role="region"
    aria-label="Fitness and Wellness Mega Navigation"
>
    <div class="theme-page-container py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 text-left">

            {{-- Column 1: EXERCISE & TRAINING --}}
            <div class="flex flex-col">
                <div class="theme-mega-column-heading">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Exercise & Training</span>
                </div>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('fitness.exercise') }}" class="theme-mega-link">
                        <span>Exercise Guides</span>
                        <span class="theme-mega-badge">Mechanics</span>
                    </a>
                    <a href="{{ route('fitness.cardio') }}" class="theme-mega-link">
                        <span>Cardio & Conditioning</span>
                        <span class="theme-mega-badge">Fat Loss</span>
                    </a>
                    <a href="{{ route('fitness.strength-training') }}" class="theme-mega-link">
                        <span>Strength Training</span>
                        <span class="theme-mega-badge">Hypertrophy</span>
                    </a>
                    <a href="{{ route('fitness.yoga') }}" class="theme-mega-link">
                        <span>Yoga & Mobility</span>
                        <span class="theme-mega-badge">Flexibility</span>
                    </a>
                    <a href="{{ route('fitness.holistic-fitness') }}" class="theme-mega-link">
                        <span>Holistic Fitness</span>
                        <span class="theme-mega-badge">Recovery</span>
                    </a>
                    <a href="{{ route('fitness.exercise-library') }}" class="theme-mega-link font-semibold">
                        <span>Exercise Library</span>
                        <span class="theme-mega-badge">25+ Movements</span>
                    </a>
                </div>
            </div>

            {{-- Column 2: WELLNESS --}}
            <div class="flex flex-col">
                <div class="theme-mega-column-heading">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>Wellness</span>
                </div>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('fitness.wellness') }}" class="theme-mega-link font-semibold">
                        <span>Wellness Editorial Hub</span>
                        <span class="theme-mega-badge">New Hub</span>
                    </a>
                    <a href="{{ route('fitness.wellness') }}#mental-wellbeing" class="theme-mega-link">
                        <span>Mind & Mental Well-Being</span>
                    </a>
                    <a href="{{ route('fitness.wellness') }}#sleep-recovery" class="theme-mega-link">
                        <span>Sleep & Recovery</span>
                        <span class="theme-mega-badge">Circadian</span>
                    </a>
                    <a href="{{ route('fitness.wellness') }}#stress-management" class="theme-mega-link">
                        <span>Stress Management</span>
                    </a>
                    <a href="{{ route('fitness.wellness') }}#healthy-habits" class="theme-mega-link">
                        <span>Healthy Habits & Mindfulness</span>
                    </a>
                    <a href="{{ route('fitness.wellness') }}#longevity" class="theme-mega-link">
                        <span>Healthy Aging & Vitality</span>
                    </a>
                    <a href="{{ route('blog.category', 'lifestyle-and-wellness') }}" class="theme-mega-link">
                        <span>Lifestyle & Wellness</span>
                    </a>
                </div>
            </div>

            {{-- Column 3: NUTRITION --}}
            <div class="flex flex-col">
                <div class="theme-mega-column-heading">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Nutrition</span>
                </div>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('blog.category', 'nutrition') }}" class="theme-mega-link font-semibold">
                        <span>Nutrition Science</span>
                        <span class="theme-mega-badge">Evidence</span>
                    </a>
                    <a href="{{ route('blog.category', 'indian-diet') }}" class="theme-mega-link">
                        <span>Indian Diet & Fuel</span>
                        <span class="theme-mega-badge">Vegetarian</span>
                    </a>
                    <a href="{{ route('blog.category', 'healthy-recipes') }}" class="theme-mega-link">
                        <span>Healthy Recipes</span>
                        <span class="theme-mega-badge">75+ Meals</span>
                    </a>
                    <a href="{{ route('blog.category', 'weight-loss') }}" class="theme-mega-link">
                        <span>Weight Loss Nutrition</span>
                    </a>
                    <a href="{{ route('blog.tag', 'high-protein') }}" class="theme-mega-link">
                        <span>High-Protein & Macros</span>
                    </a>
                    <a href="{{ route('blog.category', 'muscle-building') }}" class="theme-mega-link">
                        <span>Muscle Building Fuel</span>
                    </a>
                </div>
            </div>

            {{-- Column 4: LIFESTYLE & RECOVERY --}}
            <div class="flex flex-col">
                <div class="theme-mega-column-heading">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Lifestyle & Recovery</span>
                </div>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('blog.category', 'lifestyle-and-wellness') }}" class="theme-mega-link">
                        <span>Active Lifestyle</span>
                    </a>
                    <a href="{{ route('fitness.exercise-library', ['category' => 'Yoga & Flexibility']) }}" class="theme-mega-link">
                        <span>Mobility & Recovery</span>
                    </a>
                    <a href="{{ route('fitness.wellness') }}#sleep-recovery" class="theme-mega-link">
                        <span>Sleep & Circadian Biology</span>
                    </a>
                    <a href="{{ route('fitness.holistic-fitness') }}" class="theme-mega-link">
                        <span>Restorative Recovery</span>
                    </a>
                    <a href="{{ route('fitness.wellness') }}#longevity" class="theme-mega-link">
                        <span>Healthy Aging & Vitality</span>
                    </a>
                    <a href="{{ route('blog.category', 'mindset-and-motivation') }}" class="theme-mega-link">
                        <span>Consistency & Mindset</span>
                    </a>
                </div>
            </div>

            {{-- Column 5: FEATURED / EXPLORE --}}
            <div class="flex flex-col">
                <div class="theme-mega-column-heading">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span>Featured & Explore</span>
                </div>
                <div class="theme-mega-card flex flex-col justify-between flex-grow">
                    <div>
                        <span class="theme-mega-badge font-semibold uppercase tracking-wider mb-2 inline-block">
                            DK Singh Knowledge
                        </span>
                        <h4 class="font-bold text-base mb-2 leading-snug">
                            Fitness & Wellness Discovery Hub
                        </h4>
                        <p class="text-xs mb-4 opacity-80 leading-relaxed">
                            Access our full library of 249 original articles, movement guides, and video tutorials.
                        </p>
                    </div>

                    <div class="flex flex-col gap-2 pt-2">
                        <a href="{{ route('fitness.index') }}" class="theme-mega-link font-semibold" style="background: var(--primary-color); color: var(--primary-button-text);">
                            <span>Explore All Fitness</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <a href="{{ route('fitness.exercise-library') }}" class="theme-mega-link text-xs">
                            <span>Browse Exercise Index &rarr;</span>
                        </a>
                        <a href="{{ route('fitness.search') }}" class="theme-mega-link text-xs">
                            <span>Search Platform &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

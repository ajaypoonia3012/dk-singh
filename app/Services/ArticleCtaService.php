<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Program;
use App\Models\Service;

class ArticleCtaService
{
    /**
     * Resolve the contextual CTA payload for a given blog post.
     *
     * @param  BlogPost  $post
     * @return array{badge: string, heading: string, description: string, button_text: string, button_url: string, secondary_button_text: ?string, secondary_button_url: ?string}
     */
    public function forPost(BlogPost $post): array
    {
        $categorySlug = $post->category?->slug;
        $titleLower = strtolower($post->title ?? '');

        // 1. Yoga & Mobility intent
        if ($categorySlug === 'yoga' || str_contains($titleLower, 'yoga') || str_contains($titleLower, 'asana')) {
            return $this->yogaCta();
        }

        // 2. Weight Gain / Skinny / Hypertrophy intent (overrides accidental weight-loss category placement)
        if (str_contains($titleLower, 'skinny') || (str_contains($titleLower, 'gain weight') && !str_contains($titleLower, 'lose'))) {
            return $this->muscleBuildingCta();
        }

        // 3. Post-Pregnancy Workouts & Recovery intent
        if (str_contains($titleLower, 'post-pregnancy') || str_contains($titleLower, 'postpartum')) {
            return $this->coachingCta();
        }

        // 4. Specific Fat Loss / Belly Fat intent in general fitness category
        if ($categorySlug === 'fitness' && (str_contains($titleLower, 'belly fat') || str_contains($titleLower, 'lose 10-12kg'))) {
            return $this->weightLossCta();
        }

        // 5. Specific Nutrition intent in general fitness category
        if ($categorySlug === 'fitness' && (str_contains($titleLower, 'personalized nutrition') || str_contains($titleLower, 'smart nutrition tips') || str_contains($titleLower, 'fit your macros'))) {
            return $this->nutritionCta();
        }

        return match ($categorySlug) {
            'weight-loss' => $this->weightLossCta(),
            'workout-and-training', 'home-workouts' => $this->workoutCta(),
            'muscle-building' => $this->muscleBuildingCta(),
            'nutrition', 'indian-diet', 'healthy-recipes' => $this->nutritionCta(),
            'lifestyle-and-wellness', 'mindset-and-motivation', 'womens-fitness', 'fitness' => $this->coachingCta(),
            default => $this->fallbackCta(),
        };
    }

    /**
     * Yoga, Mobility & Recovery CTA -> Yoga & Mobility Hub.
     */
    protected function yogaCta(): array
    {
        $service = Service::where('slug', 'personal-online-coaching')->first() ?? Service::first();
        $serviceUrl = $service ? route('services.show', $service->slug) : route('services.index');

        return [
            'badge' => 'DK Singh Coaching • Mobility & Wellness',
            'heading' => 'Master Mobility & Mind-Body Recovery',
            'description' => 'Explore evidence-based yoga asanas, desk-worker mobility routines, and stress management protocols in our Yoga & Mobility Hub.',
            'button_text' => 'Explore Yoga & Mobility',
            'button_url' => route('fitness.yoga'),
            'secondary_button_text' => 'Personal Coaching',
            'secondary_button_url' => $serviceUrl,
        ];
    }

    /**
     * Weight loss CTA -> 12-Week Fat Loss Transformation.
     */
    protected function weightLossCta(): array
    {
        $program = Program::where('slug', '12-week-fat-loss-transformation')->first()
            ?? Program::where('category', 'Fat Loss')->first()
            ?? Program::first();

        $buttonUrl = $program ? route('programs.show', $program->slug) : route('programs.index');

        return [
            'badge' => 'DK Singh Coaching • Transformation Program',
            'heading' => 'Transform Your Body in 12 Weeks',
            'description' => 'Science-backed fat loss frameworks, structured calorie deficits, and weekly check-ins with Coach DK Singh.',
            'button_text' => 'Explore 12-Week Fat Loss',
            'button_url' => $buttonUrl,
            'secondary_button_text' => 'View All Programs',
            'secondary_button_url' => route('programs.index'),
        ];
    }

    /**
     * Workout & Training CTA -> Free Workout Library & Hypertrophy Program.
     */
    protected function workoutCta(): array
    {
        $program = Program::where('slug', 'lean-muscle-gain-program')->first()
            ?? Program::where('category', 'Muscle Building')->first();

        $secondaryUrl = $program ? route('programs.show', $program->slug) : route('programs.index');

        return [
            'badge' => 'DK Singh Coaching • Structured Protocols',
            'heading' => 'Level Up Your Training & Workouts',
            'description' => 'Explore structured workout routines, progressive overload splits, and video tutorials in our Workout Library.',
            'button_text' => 'Explore Workout Library',
            'button_url' => route('fitness-hub.workouts.index'),
            'secondary_button_text' => 'Muscle Gain Program',
            'secondary_button_url' => $secondaryUrl,
        ];
    }

    /**
     * Muscle Building CTA -> Lean Muscle Gain Program.
     */
    protected function muscleBuildingCta(): array
    {
        $program = Program::where('slug', 'lean-muscle-gain-program')->first()
            ?? Program::where('category', 'Muscle Building')->first()
            ?? Program::first();

        $buttonUrl = $program ? route('programs.show', $program->slug) : route('programs.index');

        return [
            'badge' => 'DK Singh Coaching • Hypertrophy Program',
            'heading' => 'Build Lean, Dense Muscle Naturally',
            'description' => 'Evidence-based hypertrophy splits, optimal volume progression, and macro coaching for natural muscle growth.',
            'button_text' => 'View Muscle Gain Program',
            'button_url' => $buttonUrl,
            'secondary_button_text' => 'Workout Library',
            'secondary_button_url' => route('fitness-hub.workouts.index'),
        ];
    }

    /**
     * Nutrition, Indian Diet & Healthy Recipes CTA -> Diet & Nutrition Coaching.
     */
    protected function nutritionCta(): array
    {
        $service = Service::where('slug', 'diet-nutrition-coaching')->first()
            ?? Service::where('title', 'like', '%Diet%')->first()
            ?? Service::first();

        $buttonUrl = $service ? route('services.show', $service->slug) : route('services.index');

        return [
            'badge' => 'DK Singh Coaching • Personalized Nutrition',
            'heading' => 'Custom Indian Macro & Meal Coaching',
            'description' => 'Custom Indian macro meal plans and nutrition coaching designed to fit your culture, lifestyle, and busy routine.',
            'button_text' => 'Explore Nutrition Coaching',
            'button_url' => $buttonUrl,
            'secondary_button_text' => 'Browse Free Diet Plans',
            'secondary_button_url' => route('fitness-hub.diets.index'),
        ];
    }

    /**
     * Lifestyle, Mindset, Women's Fitness, General Fitness CTA -> 1-on-1 Personal Coaching.
     */
    protected function coachingCta(): array
    {
        $service = Service::where('slug', 'personal-online-coaching')->first()
            ?? Service::first();

        $buttonUrl = $service ? route('services.show', $service->slug) : route('services.index');

        return [
            'badge' => 'DK Singh Coaching • 1-on-1 Mentorship',
            'heading' => 'Personal Online Coaching With DK Singh',
            'description' => 'Direct 1-on-1 accountability, customized lifestyle protocols, and sustainable habit coaching tailored to your lifestyle.',
            'button_text' => 'Apply for 1-on-1 Coaching',
            'button_url' => $buttonUrl,
            'secondary_button_text' => 'Explore Fitness Hub',
            'secondary_button_url' => route('fitness.index'),
        ];
    }

    /**
     * Fallback CTA for uncategorized or unmatched articles.
     */
    protected function fallbackCta(): array
    {
        return [
            'badge' => 'DK Singh Coaching',
            'heading' => 'Achieve Real, Sustainable Results',
            'description' => 'Customized workout splits, Indian macro-calculated meal plans, and weekly progress check-ins with Coach DK Singh.',
            'button_text' => 'View Programs',
            'button_url' => route('programs.index'),
            'secondary_button_text' => null,
            'secondary_button_url' => null,
        ];
    }
}

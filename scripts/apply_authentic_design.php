<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Models\HeroSetting;
use App\Models\ThemeSetting;
use App\Models\Media;
use Illuminate\Support\Facades\Cache;

echo "Applying authentic DK Singh Fitness configuration...\n";

// 1. Update ThemeSetting to ocean-blue (as developed and evidenced)
$theme = ThemeSetting::first() ?? new ThemeSetting();
$theme->applyPalette('ocean-blue');
$theme->theme_name = 'Ocean Blue';
$theme->save();
echo "Theme applied: ocean-blue\n";

// 2. Resolve the dk-hero media record
$heroMedia = Media::where('name', 'like', '%dk-hero%')->first();
$heroMediaId = $heroMedia ? $heroMedia->id : 25;

// 3. Update HeroSetting
$hero = HeroSetting::first() ?? new HeroSetting();
$hero->heading = "Transform Your Body,\nElevate Your Mind";
$hero->subheading = "Elite 1-on-1 coaching, science-backed workout plans, and customized nutrition strategies crafted by DK Singh.";
$hero->button_text = "Join Now";
$hero->button_link = "/plans";
$hero->view_button_text = "View Real Results";
$hero->view_button_link = "/transformations";
$hero->followers_label = "Members";
$hero->years_label = "Experience";
$hero->transformations_label = "Success Stories";
$hero->background_media_id = $heroMediaId;
$hero->overlay_color = "#000000";
$hero->overlay_opacity = 20;
$hero->enabled = true;
$hero->save();
echo "HeroSetting updated with clean production copy.\n";

// 4. Update Setting
$setting = Setting::first();
if ($setting) {
    $setting->update([
        'site_name' => 'DK Singh Fitness',
        'site_tagline' => 'Elite Online Fitness Coaching & Nutrition',
        'hero_title' => "Transform Your Body, Transform Your Life",
        'hero_subtitle' => "Elite 1-on-1 coaching, science-backed workout plans, and customized nutrition strategies crafted by DK Singh.",
        'cta_button_text' => 'Join Now',
        'cta_button_link' => '/plans',
        'view_programs_text' => 'Explore All Programs',
        'view_transformations_text' => 'View Real Results',
        'instagram_followers' => '3M+',
        'years_experience' => '14+',
        'transformations' => '5000+',
        'hero_card_title' => '16+ Years Experience',
        'about_title' => 'About Coach DK Singh',
        'about_description' => 'With over 14 years of coaching experience and 5,000+ client transformations, DK Singh combines exercise science and tailored nutrition to deliver permanent lifestyle change.',
        'about_description_2' => 'Whether you want to build lean muscle, strip stubborn body fat, or optimize your energy, our coaching protocols are personalized to your body and schedule.',
        'about_cta_text' => 'Start Your Transformation',
        'services_heading' => 'Online Coaching',
        'services_description' => 'Professional coaching and personalized nutrition plans.',
        'services_page_label' => 'Elite Coaching Services',
        'services_page_description' => 'Comprehensive 1-on-1 coaching, personalized workout programming, and metabolic nutrition designed for real, sustainable results.',
        'programs_heading' => 'Transformation Programs',
        'programs_description' => 'Personalized programs for weight loss, muscle gain, and peak performance.',
        'programs_page_label' => 'Customized Transformation Programs',
        'programs_page_description' => 'Structured training protocols engineered for rapid fat loss, lean muscle hypertrophy, and sustained athletic performance.',
        'transformations_heading' => 'Real Client Transformations',
        'transformations_page_title' => 'Real Client Transformations',
        'transformations_page_description' => 'Real people, real discipline, real transformations. Witness the proof of science-based coaching and customized nutrition.',
        'verified_client_label' => 'Verified Client',
        'testimonials_title' => 'Client Reviews',
        'testimonials_heading' => 'What Clients Say',
        'testimonials_description' => 'Thousands of successful transformations backed by sustainable discipline.',
        'bmi_label' => 'Free Tool',
        'bmi_heading' => 'BMI Calculator',
        'bmi_description' => 'Calculate your body mass index instantly to understand your baseline health.',
        'contact_title' => 'Get In Touch',
        'contact_heading' => 'Talk To Our Experts',
        'contact_description' => 'Ready to transform? Reach out today for program guidance and consultation.',
        'contact_cta_text' => 'Get Started Today',
        'address' => 'Jaipur, Rajasthan, India',
        'footer_text' => 'DK Singh Fitness & Nutrition — Empowering lives through science-backed fitness coaching, nutrition protocols, and sustainable lifestyle transformation.',
        'meta_title' => 'DK Singh Fitness | Elite Online Fitness Coaching & Nutrition',
        'meta_description' => 'Elite 1-on-1 coaching, science-backed workout plans, and customized nutrition strategies crafted by DK Singh.',
    ]);
    echo "Setting updated with clean production copy.\n";
}

// 5. Clear all caches
Cache::flush();
\App\Providers\AppServiceProvider::clearSharedViewData();
echo "Caches flushed successfully.\n";

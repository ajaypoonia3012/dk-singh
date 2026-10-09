<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transformation;
use App\Models\Testimonial;
use App\Models\Service;
use App\Models\Setting;
use App\Models\HeroSetting;

echo "=== TRANSFORMATIONS ===\n";
$transformations = Transformation::all();
echo "Count: " . $transformations->count() . "\n";
foreach ($transformations as $t) {
    echo "ID: {$t->id}, Name: {$t->name}, Image: {$t->image}, Before: {$t->before_image}, After: {$t->after_image}, Slug: {$t->slug}\n";
    $fullPath = public_path('storage/' . $t->image);
    echo "  Public file (image): " . (file_exists($fullPath) ? "EXISTS" : "MISSING: $fullPath") . "\n";
    if ($t->before_image) {
        $beforePath = public_path('storage/' . $t->before_image);
        echo "  Public file (before): " . (file_exists($beforePath) ? "EXISTS" : "MISSING: $beforePath") . "\n";
    }
    echo "  Story: " . var_export($t->story, true) . "\n";
    echo "  Review: " . var_export($t->review, true) . "\n";
    echo "  Description: " . var_export($t->description, true) . "\n";
}

echo "\n=== TESTIMONIALS ===\n";
$testimonials = Testimonial::with('media')->get();
echo "Count: " . $testimonials->count() . "\n";
foreach ($testimonials as $test) {
    echo "ID: {$test->id}, Name: {$test->name}, Image: {$test->image}\n";
    $fullPath = public_path('storage/' . $test->image);
    echo "  Public file: " . (file_exists($fullPath) ? "EXISTS" : "MISSING: $fullPath") . "\n";
    echo "  Review snippet: " . substr($test->review, 0, 50) . "...\n";
}

echo "\n=== SERVICES WITH TYPOS ===\n";
$services = Service::all();
foreach ($services as $s) {
    echo "Service ID: {$s->id}, Title: {$s->title}, Slug: {$s->slug}, Subtitle: {$s->subtitle}\n";
}

echo "\n=== HERO / SETTINGS BADGES ===\n";
$setting = Setting::first();
$hero = HeroSetting::first();
echo "Setting hero_experience_badge: " . ($setting->hero_experience_badge ?? 'N/A') . "\n";
echo "HeroSetting experience_years: " . ($hero->experience_years ?? 'N/A') . "\n";
echo "HeroSetting badge_text: " . ($hero->badge_text ?? 'N/A') . "\n";

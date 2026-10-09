<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$setting = Setting::first();
if ($setting) {
    $setting->update([
        'services_page_label' => 'Elite Coaching Services',
        'services_page_description' => 'Comprehensive 1-on-1 coaching, personalized workout programming, and metabolic nutrition designed for real, sustainable results.',
        'service_label' => 'Elite Coaching & Training Services',
        'services_heading' => 'Specialized Training & Nutrition Services',
        'programs_page_label' => 'Customized Transformation Programs',
        'programs_page_description' => 'Structured training protocols engineered for rapid fat loss, lean muscle hypertrophy, and sustained athletic performance.',
        'program_label' => 'Transformational Fitness Programs',
        'programs_heading' => 'Targeted Workout & Nutrition Protocols',
        'transformations_page_label' => 'Real Client Journeys',
        'transformations_page_title' => 'Real Client Transformations',
        'transformations_page_description' => 'Real people, real discipline, real transformations. Witness the proof of science-based coaching and customized nutrition.',
        'verified_client_label' => 'Verified Transformation',
        'view_programs_text' => 'Explore All Programs',
        'view_transformations_text' => 'View Real Results',
        'home_heading' => 'DK Singh Fitness & Nutrition',
    ]);
    
    // Clear cache
    \Illuminate\Support\Facades\Cache::forget('settings');
    \Illuminate\Support\Facades\Cache::forget('app_settings');
    echo "Successfully updated Setting placeholders to branded premium text!\n";
} else {
    echo "No Setting record found.\n";
}

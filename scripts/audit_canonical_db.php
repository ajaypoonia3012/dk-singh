<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\HeroSetting;
use App\Models\ThemeSetting;
use App\Models\WebsiteSection;
use App\Models\HomepageCard;
use App\Models\Program;
use App\Models\Service;
use App\Models\Plan;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Transformation;
use App\Models\Testimonial;
use App\Models\Media;
use App\Models\Exercise;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;

$tables = DB::select('SHOW TABLES');
$tableCounts = [];
foreach ($tables as $t) {
    $tableName = current((array)$t);
    $count = DB::table($tableName)->count();
    $tableCounts[$tableName] = $count;
}

// Categorize tables
$contentTables = [
    'settings', 'hero_settings', 'theme_settings', 'website_sections', 'homepage_cards',
    'programs', 'services', 'plans', 'products', 'product_categories',
    'blog_posts', 'blog_categories', 'blog_tags', 'blog_post_tag',
    'transformations', 'testimonials', 'media', 'media_categories',
    'exercises', 'workout_plans', 'diet_plans', 'faqs'
];

$customerTransactionalTables = [
    'users', 'orders', 'order_items', 'memberships', 'membership_histories',
    'payments', 'check_ins', 'progress_logs', 'coach_notes', 'leads',
    'contact_messages', 'consultations', 'action_plans', 'workout_completions',
    'diet_completions', 'communication_logs', 'notifications',
    'personal_access_tokens', 'sessions', 'failed_jobs', 'jobs'
];

$infrastructureTables = [
    'migrations', 'cache', 'cache_locks', 'job_batches'
];

// Media disk verification
$mediaRecords = Media::all();
$mediaDiskMissing = 0;
$mediaDiskPresent = 0;
foreach ($mediaRecords as $m) {
    $filePath = storage_path('app/public/' . $m->path);
    if (file_exists($filePath)) {
        $mediaDiskPresent++;
    } else {
        $mediaDiskMissing++;
    }
}

$report = [
    'connection' => config('database.default'),
    'database_name' => DB::connection()->getDatabaseName(),
    'all_tables_count' => count($tableCounts),
    'table_counts' => $tableCounts,
    'categorized' => [
        'content' => array_intersect_key($tableCounts, array_flip($contentTables)),
        'customer_transactional' => array_intersect_key($tableCounts, array_flip($customerTransactionalTables)),
        'infrastructure' => array_intersect_key($tableCounts, array_flip($infrastructureTables)),
    ],
    'canonical_records' => [
        'setting' => Setting::first()?->toArray(),
        'hero_setting' => HeroSetting::first()?->toArray(),
        'theme_setting' => ThemeSetting::first()?->toArray(),
        'website_sections' => WebsiteSection::orderBy('sort_order')->get()->toArray(),
        'homepage_cards' => HomepageCard::where('is_active', true)->orderBy('sort_order')->get()->toArray(),
        'programs' => Program::select('id', 'title', 'slug', 'price', 'duration', 'status', 'media_id')->get()->toArray(),
        'services' => Service::select('id', 'title', 'slug', 'price', 'duration', 'status', 'media_id')->get()->toArray(),
        'plans' => Plan::select('id', 'name', 'slug', 'price', 'duration', 'status')->get()->toArray(),
        'products' => Product::select('id', 'name', 'slug', 'price', 'status', 'media_id')->get()->toArray(),
        'transformations_count' => Transformation::count(),
        'testimonials_count' => Testimonial::count(),
        'published_blog_posts' => BlogPost::where('status', true)->count(),
        'total_blog_posts' => BlogPost::count(),
        'exercises_count' => Exercise::count(),
        'workout_plans_count' => WorkoutPlan::count(),
        'diet_plans_count' => DietPlan::count(),
        'media' => [
            'total_db_records' => $mediaRecords->count(),
            'files_present_on_disk' => $mediaDiskPresent,
            'files_missing_on_disk' => $mediaDiskMissing,
        ]
    ]
];

file_put_contents(__DIR__ . '/database_canonical_audit.json', json_encode($report, JSON_PRETTY_PRINT));
echo "Canonical DB Audit Complete. Summary saved to scripts/database_canonical_audit.json\n";
echo "Total Tables: " . count($tableCounts) . "\n";
echo "Total Media DB Records: " . $mediaRecords->count() . " (Present on disk: $mediaDiskPresent, Missing: $mediaDiskMissing)\n";
echo "Total Blog Posts: " . BlogPost::count() . " (Published: " . BlogPost::where('status', true)->count() . ")\n";
echo "Total Programs: " . Program::count() . "\n";
echo "Total Services: " . Service::count() . "\n";
echo "Total Plans: " . Plan::count() . "\n";
echo "Total Products: " . Product::count() . "\n";
echo "Total Transformations: " . Transformation::count() . "\n";
echo "Total Exercises: " . Exercise::count() . "\n";

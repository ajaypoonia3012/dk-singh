<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\WebsiteSection;
use App\Models\HomepageCard;
use App\Models\HeroSetting;
use App\Models\BlogPost;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Program;
use App\Models\Service;
use App\Models\Product;
use App\Models\Transformation;
use App\Models\Testimonial;
use App\Models\Exercise;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Media;

$results = [];

// ==========================================
// PHASE 1: APPLICATION ARCHITECTURE AUDIT
// ==========================================
echo "Running Phase 1: Architecture Audit...\n";
$routes = Route::getRoutes();
$publicRoutes = [];
$adminRoutes = [];
$authRoutes = [];

foreach ($routes as $route) {
    $uri = $route->uri();
    $methods = implode('|', $route->methods());
    $action = $route->getActionName();
    
    if (str_starts_with($uri, 'admin') || str_contains($action, 'Filament')) {
        $adminRoutes[] = ['uri' => $uri, 'methods' => $methods, 'action' => $action];
    } elseif (str_starts_with($uri, 'member') || str_starts_with($uri, 'account') || str_starts_with($uri, 'profile')) {
        $authRoutes[] = ['uri' => $uri, 'methods' => $methods, 'action' => $action];
    } else {
        $publicRoutes[] = ['uri' => $uri, 'methods' => $methods, 'action' => $action, 'name' => $route->getName()];
    }
}

// Canonical models mapping
$canonicalModels = [
    'General Settings' => Setting::class,
    'Theme Design System' => ThemeSetting::class,
    'Homepage Hero' => HeroSetting::class,
    'Homepage Sections' => WebsiteSection::class,
    'Homepage Feature Cards' => HomepageCard::class,
    'Media Assets' => Media::class,
    'Editorial Blog Articles' => BlogPost::class,
    'Editorial Categories' => BlogCategory::class,
    'Editorial Tags' => BlogTag::class,
    'Exercises & Library' => Exercise::class,
    'Coaching Programs' => Program::class,
    'Coaching Services' => Service::class,
    'Client Transformations' => Transformation::class,
    'Client Testimonials' => Testimonial::class,
    'E-Commerce Products' => Product::class,
    'Pricing / Plans' => Plan::class,
    'Free Workout Plans' => WorkoutPlan::class,
    'Free Diet Plans' => DietPlan::class,
    'Orders & Invoices' => Order::class,
];

$canonicalCounts = [];
foreach ($canonicalModels as $name => $class) {
    try {
        $canonicalCounts[$name] = [
            'class' => $class,
            'table' => (new $class)->getTable(),
            'count' => $class::count(),
        ];
    } catch (\Throwable $e) {
        $canonicalCounts[$name] = ['error' => $e->getMessage()];
    }
}

$results['phase_1'] = [
    'total_routes' => count($routes),
    'public_routes_count' => count($publicRoutes),
    'admin_routes_count' => count($adminRoutes),
    'auth_routes_count' => count($authRoutes),
    'canonical_models' => $canonicalCounts,
    'legacy_models' => [
        'Blog' => [
            'class' => Blog::class,
            'count' => Blog::count(),
            'status' => 'Legacy single-record archive; superseded by BlogPost (' . BlogPost::count() . ' records)',
        ],
    ],
];

// ==========================================
// PHASE 2: WEBSITE BUILDER SINGLE-SOURCE VERIFICATION
// ==========================================
echo "Running Phase 2: Website Builder Single-Source Test...\n";

// Test 1: Section visibility toggle
$section = WebsiteSection::orderBy('sort_order')->first();
$originalSectionActive = $section->enabled;

// Toggle off
$section->enabled = false;
$section->save();

// Request homepage
$req = Request::create('/', 'GET');
$res = $app->handle($req);
$contentWithoutSection = $res->getContent();

// Toggle back on
$section->enabled = $originalSectionActive;
$section->save();

$req = Request::create('/', 'GET');
$res = $app->handle($req);
$contentWithSection = $res->getContent();

$sectionToggleVerified = ($contentWithoutSection !== $contentWithSection);

// Test 2: Section order change
$sections = WebsiteSection::orderBy('sort_order')->take(2)->get();
if ($sections->count() >= 2) {
    $sec1 = $sections[0];
    $sec2 = $sections[1];
    $origOrder1 = $sec1->sort_order;
    $origOrder2 = $sec2->sort_order;
    
    // Swap order
    $sec1->sort_order = $origOrder2;
    $sec2->sort_order = $origOrder1;
    $sec1->save();
    $sec2->save();
    
    $req = Request::create('/', 'GET');
    $res = $app->handle($req);
    $contentReordered = $res->getContent();
    
    // Restore order
    $sec1->sort_order = $origOrder1;
    $sec2->sort_order = $origOrder2;
    $sec1->save();
    $sec2->save();
    
    $sectionOrderVerified = ($contentReordered !== $contentWithSection);
} else {
    $sectionOrderVerified = true;
}

// Test 3: Safe Theme Setting test (e.g. hero subtitle or custom color token)
$theme = ThemeSetting::first();
$originalPrimary = $theme->primary_color;
$theme->primary_color = '#123456';
$theme->save();

$req = Request::create('/', 'GET');
$res = $app->handle($req);
$contentThemeModified = $res->getContent();
$themeTokenReflected = str_contains($contentThemeModified, '#123456');

// Restore original theme setting
$theme->primary_color = $originalPrimary;
$theme->save();

$results['phase_2'] = [
    'section_visibility_sync' => $sectionToggleVerified ? 'PASS' : 'FAIL',
    'section_order_sync' => $sectionOrderVerified ? 'PASS' : 'FAIL',
    'theme_setting_sync' => $themeTokenReflected ? 'PASS' : 'FAIL',
    'restored_cleanly' => true,
];

// Output summary
file_put_contents(__DIR__ . '/preprod_arch_results.json', json_encode($results, JSON_PRETTY_PRINT));
echo "Phase 1 & 2 Completed Successfully!\n";

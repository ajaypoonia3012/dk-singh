<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Program;
use App\Models\Service;
use App\Models\Plan;
use App\Models\Membership;
use App\Models\Product;
use App\Models\ContactLead;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;
use Illuminate\Http\Request;

echo "=== PHASE 2 CONVERSION & LEAD GENERATION AUDIT ===\n";

// 1. Articles by Category
$categories = BlogCategory::withCount('posts')->get();
echo "\n--- Categories & Post Counts ---\n";
foreach ($categories as $cat) {
    echo "Category: {$cat->name} (slug: {$cat->slug}) => {$cat->posts_count} posts\n";
}

$totalPosts = BlogPost::where('status', true)->count();
echo "Total published posts: {$totalPosts}\n";

// Check sample posts across categories
$sampleBreakdown = [];
foreach ($categories as $cat) {
    $samplePost = BlogPost::where('blog_category_id', $cat->id)->first();
    if ($samplePost) {
        $sampleBreakdown[$cat->slug] = [
            'category' => $cat->name,
            'sample_title' => $samplePost->title,
            'sample_slug' => $samplePost->slug,
        ];
    }
}

// 2. Commercial Offerings
echo "\n--- Programs ---\n";
foreach (Program::all() as $prog) {
    echo "- [Program #{$prog->id}] {$prog->title} (slug: {$prog->slug}) | Price: ₹{$prog->price} | Category: {$prog->category}\n";
}

echo "\n--- Services ---\n";
foreach (Service::all() as $svc) {
    echo "- [Service #{$svc->id}] {$svc->title} (slug: {$svc->slug}) | Price: ₹{$svc->price} | Duration: {$svc->duration}\n";
}

echo "\n--- Membership Plans ---\n";
foreach (Plan::all() as $p) {
    echo "- [Plan #{$p->id}] {$p->name} (slug: {$p->slug}) | Price: ₹{$p->price} | Access: {$p->access_type}\n";
}

echo "\n--- Physical Products ---\n";
foreach (Product::all() as $prod) {
    echo "- [Product #{$prod->id}] {$prod->name} | Price: ₹{$prod->price} | Category: {$prod->category}\n";
}

// 3. Free Resources
echo "\n--- Free Workout Plans (Fitness Hub) ---\n";
foreach (WorkoutPlan::all() as $wp) {
    echo "- [Workout #{$wp->id}] {$wp->title} (slug: {$wp->slug}) | Access: {$wp->required_access} | Difficulty: {$wp->difficulty}\n";
}

echo "\n--- Free Diet Plans (Fitness Hub) ---\n";
foreach (DietPlan::all() as $dp) {
    echo "- [Diet #{$dp->id}] {$dp->title} (slug: {$dp->slug}) | Access: {$dp->required_access} | Goal: {$dp->goal}\n";
}

// 4. Contact Leads Summary
echo "\n--- Contact Leads in DB ---\n";
$leadCount = ContactLead::count();
echo "Total ContactLeads: {$leadCount}\n";
$latestLeads = ContactLead::latest()->take(3)->get();
foreach ($latestLeads as $l) {
    echo "- Lead #{$l->id}: {$l->name} ({$l->email}) | Source: {$l->source} | Status: {$l->status}\n";
}

file_put_contents(__DIR__ . '/phase2_audit_raw.json', json_encode([
    'categories' => $categories->toArray(),
    'samples' => $sampleBreakdown,
    'programs' => Program::all()->toArray(),
    'services' => Service::all()->toArray(),
    'plans' => Plan::all()->toArray(),
    'products_count' => Product::count(),
    'workout_plans' => WorkoutPlan::all()->toArray(),
    'diet_plans' => DietPlan::all()->toArray(),
    'leads_count' => $leadCount,
], JSON_PRETTY_PRINT));

echo "\nAudit script completed. Results saved to phase2_audit_raw.json\n";

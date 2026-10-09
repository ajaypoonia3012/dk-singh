<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== 1. PROGRAMS ===\n";
foreach (App\Models\Program::all() as $p) {
    echo "ID: {$p->id} | Title: {$p->title} | Slug: {$p->slug} | Price: {$p->price} | Sale Price: {$p->sale_price} | Duration: {$p->duration}\n";
    echo "  Short: {$p->short_description}\n";
}

echo "\n=== 2. SERVICES ===\n";
foreach (App\Models\Service::all() as $s) {
    echo "ID: {$s->id} | Title: {$s->title} | Slug: {$s->slug} | Price: {$s->price} | Duration: {$s->duration}\n";
    echo "  Short: {$s->short_description}\n";
}

echo "\n=== 3. PLANS ===\n";
foreach (App\Models\Plan::all() as $pl) {
    echo "ID: {$pl->id} | Name: {$pl->name} | Slug: {$pl->slug} | Price: {$pl->price} | Duration: {$pl->duration_in_days} days | Featured: {$pl->is_featured}\n";
    echo "  Features: " . json_encode($pl->features) . "\n";
}

echo "\n=== 4. MEMBERSHIPS ===\n";
echo "Count: " . App\Models\Membership::count() . "\n";
foreach (App\Models\Membership::all() as $m) {
    echo "ID: {$m->id} | User: {$m->user_id} | Plan: {$m->plan_id} | Status: {$m->status} | Start: {$m->starts_at} | Ends: {$m->ends_at}\n";
}

echo "\n=== 5. CHECKOUT / ORDER LINKAGES ===\n";
echo "Unique item_types in Order: " . json_encode(App\Models\Order::select('item_type')->distinct()->pluck('item_type')) . "\n";

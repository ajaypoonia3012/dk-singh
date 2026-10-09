<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sections = \App\Models\WebsiteSection::orderBy('sort_order')->get();
echo "=== WEBSITE SECTIONS (Count: " . $sections->count() . ") ===\n";
foreach ($sections as $s) {
    echo "ID: {$s->id} | section: {$s->section} | title: {$s->title} | sort_order: {$s->sort_order} | enabled: " . ($s->enabled ? '1' : '0') . "\n";
}

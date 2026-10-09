<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$packagePath = storage_path('app/content_export/website_content_package.json');
$package = json_decode(file_get_contents($packagePath), true);
$tables = $package['tables'];

echo "=== MEDIA PACKAGE INTEGRITY AUDIT ===\n\n";

$mediaRows = $tables['media'] ?? [];
echo "Total Media Table Rows: " . count($mediaRows) . "\n";

$missingDiskFiles = [];
$existingDiskFiles = 0;
$pathCounts = [];

foreach ($mediaRows as $m) {
    $path = $m['path'] ?? '';
    if (empty($path)) {
        continue;
    }
    $pathCounts[$path] = ($pathCounts[$path] ?? 0) + 1;
    $fullPath = storage_path('app/public/' . $path);
    if (file_exists($fullPath)) {
        $existingDiskFiles++;
    } else {
        $missingDiskFiles[] = ['id' => $m['id'], 'path' => $path];
    }
}

echo "Files present on disk: {$existingDiskFiles}\n";
echo "Files missing on disk: " . count($missingDiskFiles) . "\n";

$duplicatePaths = array_filter($pathCounts, fn($cnt) => $cnt > 1);
echo "Duplicate paths in media table: " . count($duplicatePaths) . "\n";
if (!empty($duplicatePaths)) {
    print_r($duplicatePaths);
}

// Media IDs set
$mediaIds = array_column($mediaRows, 'id');
$mediaIdSet = array_flip($mediaIds);

// Audit foreign references
$foreignReferences = [
    'blog_posts' => ['media_id'],
    'products' => ['media_id'],
    'programs' => ['media_id'],
    'services' => ['media_id'],
    'testimonials' => ['media_id'],
    'transformation_photos' => ['media_id'],
    'exercises' => ['media_id'],
    'hero_settings' => ['hero_image_id', 'mobile_hero_image_id', 'video_media_id'],
    'theme_settings' => ['public_login_background_media_id', 'admin_login_background_media_id'],
];

echo "\n--- Foreign Media Reference Validation ---\n";
$brokenRefs = [];

foreach ($foreignReferences as $tbl => $cols) {
    if (!isset($tables[$tbl])) continue;
    foreach ($tables[$tbl] as $row) {
        foreach ($cols as $col) {
            if (!empty($row[$col])) {
                $refId = $row[$col];
                if (!isset($mediaIdSet[$refId])) {
                    $brokenRefs[] = "Table '{$tbl}' (Row ID: {$row['id']}) references non-existent media ID: {$refId} in column '{$col}'";
                }
            }
        }
    }
}

if (empty($brokenRefs)) {
    echo "All foreign media references resolve to valid media table IDs! (0 broken references)\n";
} else {
    echo "Found " . count($brokenRefs) . " broken references:\n";
    print_r($brokenRefs);
}

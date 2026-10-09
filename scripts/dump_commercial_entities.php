<?php

$packagePath = __DIR__ . '/../storage/app/content_export/website_content_package.json';
$data = json_decode(file_get_contents($packagePath), true)['tables'];

echo "=== PRODUCTS (11 records, IDs: 1-12, skipping 8) ===\n";
foreach ($data['products'] as $p) {
    echo sprintf("ID %-2d | %-32s | Rs. %-8s | slug: %s\n", $p['id'], $p['name'], $p['price'], $p['slug']);
}

echo "\n=== PLANS (4 records, IDs: 1-4) ===\n";
foreach ($data['plans'] as $pl) {
    echo sprintf("ID %-2d | %-32s | Rs. %-8s | slug: %s\n", $pl['id'], $pl['name'], $pl['price'], $pl['slug']);
}

echo "\n=== PROGRAMS (4 records, IDs: 1-4) ===\n";
foreach ($data['programs'] as $pr) {
    echo sprintf("ID %-2d | %-32s | Rs. %-8s | slug: %s\n", $pr['id'], $pr['title'] ?? '', $pr['price'] ?? '0.00', $pr['slug']);
}

echo "\n=== SERVICES (3 records, IDs: 1-3) ===\n";
foreach ($data['services'] as $s) {
    echo sprintf("ID %-2d | %-32s | slug: %s\n", $s['id'], $s['title'] ?? '', $s['slug']);
}

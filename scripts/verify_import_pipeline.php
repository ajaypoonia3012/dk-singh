<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$packagePath = storage_path('app/content_export/website_content_package.json');
$data = json_decode(file_get_contents($packagePath), true);

$arrayColumns = [];
foreach ($data['tables'] as $table => $rows) {
    foreach ($rows as $r) {
        foreach ($r as $k => $v) {
            if (is_array($v)) {
                $arrayColumns[] = "$table.$k";
            }
        }
    }
}
echo "Array columns found in exported JSON:\n";
print_r(array_unique($arrayColumns));

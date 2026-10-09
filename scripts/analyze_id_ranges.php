<?php

$packagePath = __DIR__ . '/../storage/app/content_export/website_content_package.json';
$data = json_decode(file_get_contents($packagePath), true);

echo sprintf("%-24s | %-6s | %-8s | %-8s | %-12s | %s\n", "Table", "Count", "Min ID", "Max ID", "Contiguous?", "ID Samples");
echo str_repeat("-", 85) . "\n";

foreach ($data['tables'] as $table => $rows) {
    $ids = array_filter(array_column($rows, 'id'));
    if (!empty($ids)) {
        $min = min($ids);
        $max = max($ids);
        $contiguous = (count($ids) === ($max - $min + 1)) ? 'YES' : 'NO';
        $sample = implode(',', array_slice($ids, 0, 5)) . (count($ids) > 5 ? '...' : '');
        echo sprintf("%-24s | %-6d | %-8d | %-8d | %-12s | %s\n", $table, count($ids), $min, $max, $contiguous, $sample);
    } else {
        echo sprintf("%-24s | %-6d | %-8s | %-8s | %-12s | %s\n", $table, count($rows), 'N/A', 'N/A', 'N/A', 'pivot');
    }
}

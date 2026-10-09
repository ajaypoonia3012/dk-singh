<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportWebsiteContent extends Command
{
    protected $signature = 'content:export {--output= : Custom export JSON filepath}';

    protected $description = 'Safely export canonical website content and configuration to a repeatable JSON package';

    /**
     * Whitelist of content tables in strict foreign-key dependency order.
     */
    protected array $contentTables = [
        'media_categories',
        'media',
        'settings',
        'hero_settings',
        'theme_settings',
        'website_sections',
        'homepage_cards',
        'blog_categories',
        'blog_tags',
        'blog_posts',
        'blog_post_tag',
        'programs',
        'services',
        'plans',
        'products',
        'transformations',
        'transformation_photos',
        'testimonials',
        'exercises',
        'workout_plans',
        'diet_plans',
        'courier_providers',
    ];

    public function handle(): int
    {
        $this->info('Starting canonical website content export...');

        $outputPath = $this->option('output') 
            ?: storage_path('app/content_export/website_content_package.json');

        File::ensureDirectoryExists(dirname($outputPath));

        $exportData = [
            'exported_at' => now()->toIso8601String(),
            'app_version' => config('app.version', '1.0.0'),
            'tables' => [],
            'row_counts' => [],
        ];

        $totalRows = 0;
        $tableSummary = [];

        foreach ($this->contentTables as $table) {
            if (! DB::getSchemaBuilder()->hasTable($table)) {
                $this->warn("Table '{$table}' does not exist, skipping.");
                continue;
            }

            $rows = DB::table($table)->get()->map(fn ($r) => (array) $r)->toArray();
            $count = count($rows);
            $totalRows += $count;

            $exportData['tables'][$table] = $rows;
            $exportData['row_counts'][$table] = $count;

            $tableSummary[] = [
                'table' => $table,
                'rows' => $count,
            ];
        }

        $exportData['total_tables'] = count($exportData['tables']);
        $exportData['total_rows'] = $totalRows;
        $exportData['checksum'] = hash('sha256', json_encode($exportData['tables']));

        File::put($outputPath, json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->table(['Table Name', 'Row Count'], $tableSummary);
        $this->info("Export successfully created at: {$outputPath}");
        $this->info("Total Tables: {$exportData['total_tables']} | Total Content Records: {$totalRows}");

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Providers\AppServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportWebsiteContent extends Command
{
    protected $signature = 'content:import 
                            {--file= : Path to content package JSON}
                            {--dry-run : Validate and simulate import without writing to database}
                            {--backup : Force full table backup before importing}
                            {--restore= : Path to backup directory to restore}';

    protected $description = 'Safely and idempotently import canonical website content while strictly preserving customer/order data';

    /**
     * Forbidden tables that must NEVER be modified by content import.
     */
    protected array $forbiddenTables = [
        'users',
        'orders',
        'order_items',
        'memberships',
        'membership_histories',
        'payments',
        'progress_logs',
        'weekly_check_ins',
        'action_plans',
        'coach_notes',
        'workout_completions',
        'diet_completions',
        'contact_leads',
        'shipments',
        'shipment_events',
        'communication_logs',
        'notifications',
        'security_audit_logs',
        'sessions',
        'password_reset_tokens',
        'personal_access_tokens',
    ];

    /**
     * Whitelist of allowed content tables in strict insertion order.
     */
    protected array $allowedContentTables = [
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
        if ($restoreDir = $this->option('restore')) {
            return $this->handleRestore($restoreDir);
        }

        $filePath = $this->option('file') 
            ?: storage_path('app/content_export/website_content_package.json');

        if (! File::exists($filePath)) {
            $this->error("Content package file not found at: {$filePath}");
            return self::FAILURE;
        }

        $this->info("Loading content package from: {$filePath}");
        $content = json_decode(File::get($filePath), true);

        if (! isset($content['tables']) || ! is_array($content['tables'])) {
            $this->error('Invalid package format: missing "tables" element.');
            return self::FAILURE;
        }

        // Safety Check 1: Forbidden tables check
        foreach (array_keys($content['tables']) as $tableName) {
            if (in_array($tableName, $this->forbiddenTables, true)) {
                $this->error("CRITICAL SAFETY VIOLATION: Package contains forbidden table '{$tableName}'. Import aborted!");
                return self::FAILURE;
            }
            if (! in_array($tableName, $this->allowedContentTables, true)) {
                $this->warn("Unrecognized table '{$tableName}' in package. Skipping.");
            }
        }

        $isDryRun = (bool) $this->option('dry-run');
        if ($isDryRun) {
            $this->warn('--- DRY RUN MODE: No database changes will be committed ---');
        }

        // Safety Check 2: Pre-import backup
        $backupDir = storage_path('backups/pre_content_import_' . date('Ymd_His'));
        if (! $isDryRun && ($this->option('backup') || true)) {
            File::ensureDirectoryExists($backupDir);
            $this->info("Creating pre-import backup in {$backupDir}...");
            foreach ($this->allowedContentTables as $tbl) {
                if (DB::getSchemaBuilder()->hasTable($tbl)) {
                    $existingData = DB::table($tbl)->get()->map(fn ($r) => (array) $r)->toArray();
                    File::put("{$backupDir}/{$tbl}.json", json_encode($existingData, JSON_PRETTY_PRINT));
                }
            }
            $this->info('Pre-import snapshot created successfully.');
        }

        $importSummary = [];

        DB::beginTransaction();
        try {
            $this->disableForeignKeys();

            foreach ($this->allowedContentTables as $table) {
                if (! isset($content['tables'][$table])) {
                    continue;
                }

                $records = $content['tables'][$table];
                $rowCount = count($records);
                $insertedOrUpdated = 0;

                if (! DB::getSchemaBuilder()->hasTable($table)) {
                    $this->warn("Table '{$table}' does not exist in target database. Skipping.");
                    continue;
                }

                if (! $isDryRun) {
                    foreach ($records as $record) {
                        foreach ($record as $key => $val) {
                            if (is_array($val)) {
                                $record[$key] = json_encode($val);
                            }
                        }

                        // If record has an 'id' column, use it as unique key
                        if (isset($record['id'])) {
                            DB::table($table)->updateOrInsert(
                                ['id' => $record['id']],
                                $record
                            );
                        } elseif ($table === 'blog_post_tag' && isset($record['blog_post_id'], $record['blog_tag_id'])) {
                            // Pivot table
                            DB::table($table)->updateOrInsert(
                                [
                                    'blog_post_id' => $record['blog_post_id'],
                                    'blog_tag_id' => $record['blog_tag_id'],
                                ],
                                $record
                            );
                        } else {
                            DB::table($table)->insert($record);
                        }
                        $insertedOrUpdated++;
                    }
                } else {
                    $insertedOrUpdated = $rowCount;
                }

                $importSummary[] = [
                    'table' => $table,
                    'records' => $rowCount,
                    'status' => $isDryRun ? 'Dry-Run Valid' : 'Upserted Successfully',
                ];
            }

            $this->enableForeignKeys();

            if ($isDryRun) {
                DB::rollBack();
                $this->info('Dry-run complete. Database remained untouched.');
            } else {
                DB::commit();
                $this->info('All database updates successfully committed.');

                // Clear caches
                Cache::flush();
                AppServiceProvider::clearSharedViewData();
                $this->info('Theme and settings cache cleared.');
            }

            $this->table(['Table Name', 'Record Count', 'Status'], $importSummary);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->enableForeignKeys();
            DB::rollBack();
            $this->error("Import encountered error: {$e->getMessage()}. Rolled back completely!");
            return self::FAILURE;
        }
    }

    protected function handleRestore(string $restoreDir): int
    {
        if (! File::isDirectory($restoreDir)) {
            $this->error("Restore directory does not exist: {$restoreDir}");
            return self::FAILURE;
        }

        $this->warn("Restoring content from backup snapshot: {$restoreDir}");

        DB::beginTransaction();
        try {
            $this->disableForeignKeys();

            foreach ($this->allowedContentTables as $table) {
                $filePath = "{$restoreDir}/{$table}.json";
                if (! File::exists($filePath)) {
                    continue;
                }

                $records = json_decode(File::get($filePath), true);
                if (! is_array($records)) {
                    continue;
                }

                DB::table($table)->truncate();
                foreach ($records as $record) {
                    foreach ($record as $k => $v) {
                        if (is_array($v)) {
                            $record[$k] = json_encode($v);
                        }
                    }
                    DB::table($table)->insert($record);
                }
                $this->info("Restored {$table}: " . count($records) . " record(s).");
            }

            $this->enableForeignKeys();
            DB::commit();

            Cache::flush();
            AppServiceProvider::clearSharedViewData();
            $this->info('Restore completed successfully and caches cleared.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->enableForeignKeys();
            DB::rollBack();
            $this->error("Restore failed: {$e->getMessage()}");
            return self::FAILURE;
        }
    }

    protected function disableForeignKeys(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
    }

    protected function enableForeignKeys(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}

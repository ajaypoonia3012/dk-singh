<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ContentSyncSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_package_file_exists_and_matches_checksum(): void
    {
        $packagePath = storage_path('app/content_export/website_content_package.json');
        $this->assertFileExists($packagePath);

        $package = json_decode(File::get($packagePath), true);
        $this->assertIsArray($package);
        $this->assertArrayHasKey('checksum', $package);
        $this->assertArrayHasKey('tables', $package);
        $this->assertArrayHasKey('total_rows', $package);
        $this->assertSame(1143, $package['total_rows']);
        $this->assertCount(22, $package['tables']);

        $computedChecksum = hash('sha256', json_encode($package['tables']));
        $this->assertSame($package['checksum'], $computedChecksum);
    }

    public function test_import_strictly_rejects_forbidden_tables(): void
    {
        $tempPath = storage_path('app/content_export/test_malicious_package.json');
        
        $payload = [
            'tables' => [
                'users' => [
                    ['id' => 999, 'name' => 'Injected User', 'email' => 'hacker@example.com'],
                ],
                'settings' => [
                    ['id' => 1, 'site_name' => 'Hacked Site'],
                ],
            ],
        ];

        File::put($tempPath, json_encode($payload));

        try {
            $exitCode = $this->artisan('content:import', [
                '--file' => $tempPath,
                '--dry-run' => true,
            ])->assertFailed()->run();

            $this->assertSame(1, $exitCode);
        } finally {
            if (File::exists($tempPath)) {
                File::delete($tempPath);
            }
        }
    }

    public function test_dry_run_does_not_modify_database(): void
    {
        $packagePath = storage_path('app/content_export/website_content_package.json');
        $this->assertFileExists($packagePath);

        $this->artisan('content:import', [
            '--file' => $packagePath,
            '--dry-run' => true,
        ])->assertSuccessful();

        // In a fresh test DB, dry run must leave tables empty
        $this->assertSame(0, DB::table('blog_posts')->count());
        $this->assertSame(0, DB::table('media')->count());
    }

    public function test_backup_and_restore_cycle_on_database_with_existing_fk_relationships(): void
    {
        // 1. Setup existing relational rows across foreign keys
        DB::table('media_categories')->insert([
            'id' => 99,
            'name' => 'Original Category',
            'slug' => 'original-category',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('media')->insert([
            'id' => 888,
            'media_category_id' => 99,
            'name' => 'Original Media',
            'file_name' => 'original.webp',
            'disk' => 'public',
            'folder' => 'original',
            'path' => 'original/original.webp',
            'type' => 'image',
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('blog_categories')->insert([
            'id' => 55,
            'name' => 'Nutrition Category',
            'slug' => 'nutrition',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('blog_posts')->insert([
            'id' => 777,
            'blog_category_id' => 55,
            'media_id' => 888,
            'title' => 'Original Blog Post',
            'slug' => 'original-blog-post',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'status' => 'published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Take a snapshot
        $backupDir = storage_path('backups/test_snapshot_' . uniqid());
        File::ensureDirectoryExists($backupDir);
        File::put("{$backupDir}/media_categories.json", json_encode(DB::table('media_categories')->get()->map(fn ($r) => (array) $r)->toArray()));
        File::put("{$backupDir}/media.json", json_encode(DB::table('media')->get()->map(fn ($r) => (array) $r)->toArray()));
        File::put("{$backupDir}/blog_categories.json", json_encode(DB::table('blog_categories')->get()->map(fn ($r) => (array) $r)->toArray()));
        File::put("{$backupDir}/blog_posts.json", json_encode(DB::table('blog_posts')->get()->map(fn ($r) => (array) $r)->toArray()));

        try {
            // 3. Mutate the database
            DB::table('media')->where('id', 888)->update(['name' => 'Mutated Media']);
            DB::table('media_categories')->where('id', 99)->update(['name' => 'Mutated Category']);
            DB::table('blog_posts')->where('id', 777)->update(['title' => 'Mutated Post Title']);

            $this->assertSame('Mutated Media', DB::table('media')->where('id', 888)->value('name'));

            // 4. Run restore
            $this->artisan('content:import', ['--restore' => $backupDir])->assertSuccessful();

            // 5. Verify restored state & FK integrity
            $this->assertSame('Original Media', DB::table('media')->where('id', 888)->value('name'));
            $this->assertSame('Original Category', DB::table('media_categories')->where('id', 99)->value('name'));
            $this->assertSame(99, (int) DB::table('media')->where('id', 888)->value('media_category_id'));
            $this->assertSame('Original Blog Post', DB::table('blog_posts')->where('id', 777)->value('title'));
            $this->assertSame(55, (int) DB::table('blog_posts')->where('id', 777)->value('blog_category_id'));
            $this->assertSame(888, (int) DB::table('blog_posts')->where('id', 777)->value('media_id'));
        } finally {
            if (File::isDirectory($backupDir)) {
                File::deleteDirectory($backupDir);
            }
        }
    }

    public function test_importer_strictly_rejects_missing_or_corrupt_checksum(): void
    {
        $testPath = storage_path('app/content_export/corrupt_checksum_package.json');
        
        // 1. Missing checksum
        $payload1 = [
            'tables' => [
                'settings' => [['id' => 1, 'site_name' => 'Test']],
            ],
        ];
        File::put($testPath, json_encode($payload1));
        $this->artisan('content:import', ['--file' => $testPath])->assertFailed();

        // 2. Corrupt / mismatched checksum
        $payload2 = [
            'checksum' => 'invalid_sha256_hash_here',
            'tables' => [
                'settings' => [['id' => 1, 'site_name' => 'Test']],
            ],
        ];
        File::put($testPath, json_encode($payload2));
        $this->artisan('content:import', ['--file' => $testPath])->assertFailed();

        if (File::exists($testPath)) {
            File::delete($testPath);
        }
    }

    public function test_foreign_key_checks_are_restored_in_finally_block_when_import_fails(): void
    {
        $malformedPackage = storage_path('app/content_export/malformed_test_package.json');
        // Contains an invalid column that triggers SQL exception during table insert
        $tables = [
            'media_categories' => [
                ['id' => 1, 'non_existent_column_fail' => 'bad_data'],
            ],
        ];
        $payload = [
            'checksum' => hash('sha256', json_encode($tables)),
            'tables' => $tables,
        ];
        File::put($malformedPackage, json_encode($payload));

        try {
            $this->artisan('content:import', ['--file' => $malformedPackage])->assertFailed();

            // Verify foreign keys are enabled (in SQLite PRAGMA foreign_keys is 1, in MySQL FOREIGN_KEY_CHECKS is 1)
            if (DB::getDriverName() === 'sqlite') {
                $fkStatus = DB::select('PRAGMA foreign_keys;');
                $this->assertEquals(1, (int) array_values((array) $fkStatus[0])[0]);
            } else {
                $fkStatus = DB::select('SELECT @@FOREIGN_KEY_CHECKS as fk;');
                $this->assertEquals(1, (int) $fkStatus[0]->fk);
            }
        } finally {
            if (File::exists($malformedPackage)) {
                File::delete($malformedPackage);
            }
        }
    }

    public function test_transactional_rollback_reverts_content_and_preserves_protected_tables(): void
    {
        // 1. Seed existing customer and order in protected tables
        $userId = DB::table('users')->insertGetId([
            'name' => 'Live Customer',
            'email' => 'customer@live.com',
            'password' => bcrypt('secret123'),
            'account_type' => 'customer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $userId,
            'order_number' => 'ORD-LIVE-9999',
            'order_status' => 'processing',
            'payment_status' => 'paid',
            'customer_name' => 'Live Customer',
            'customer_email' => 'customer@live.com',
            'amount' => 2999.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seed existing settings row
        DB::table('settings')->insert([
            'id' => 1,
            'site_name' => 'Pre-Import Site Name',
            'email' => 'contact@original.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create a payload that updates settings (table 3) but errors on a subsequent table (e.g. invalid column on table 8)
        $failingPackage = storage_path('app/content_export/rollback_test_package.json');
        $tables = [
            'settings' => [
                ['id' => 1, 'site_name' => 'Should Be Rolled Back Site Name'],
            ],
            'blog_categories' => [
                ['id' => 999, 'illegal_column_error' => 'crash'],
            ],
        ];
        $payload = [
            'checksum' => hash('sha256', json_encode($tables)),
            'tables' => $tables,
        ];
        File::put($failingPackage, json_encode($payload));

        try {
            $this->artisan('content:import', ['--file' => $failingPackage])->assertFailed();

            // 4. Assert protected tables are completely untouched
            $this->assertDatabaseHas('users', ['id' => $userId, 'email' => 'customer@live.com']);
            $this->assertDatabaseHas('orders', ['id' => $orderId, 'order_number' => 'ORD-LIVE-9999']);

            // 5. Assert content modification was completely rolled back
            $this->assertSame('Pre-Import Site Name', DB::table('settings')->where('id', 1)->value('site_name'));
        } finally {
            if (File::exists($failingPackage)) {
                File::delete($failingPackage);
            }
        }
    }
}

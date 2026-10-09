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
}

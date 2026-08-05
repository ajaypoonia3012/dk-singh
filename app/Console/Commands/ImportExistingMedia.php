<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportExistingMedia extends Command
{
    protected $signature = 'media:import';

    protected $description = 'Import existing uploaded files into the Media Library';

    public function handle()
    {
        $disk = Storage::disk('public');

        $files = $disk->allFiles();

        $count = 0;
        $skipped = 0;

        foreach ($files as $path) {

            if ($path === '.gitignore') {
                continue;
            }

            if (str_starts_with($path, 'media/')) {
                continue;
            }

            if (Media::where('path', $path)->exists()) {
                $skipped++;
                continue;
            }

            $absolute = storage_path('app/public/'.$path);

            if (! File::exists($absolute)) {
                continue;
            }

            $mime = File::mimeType($absolute);
            $size = File::size($absolute);

            $width = null;
            $height = null;

            if (@getimagesize($absolute)) {
                [$width, $height] = getimagesize($absolute);
            }

            Media::create([
                'name' => pathinfo($path, PATHINFO_FILENAME),
                'file_name' => basename($path),
                'disk' => 'public',
                'folder' => dirname($path),
                'path' => $path,
                'mime_type' => $mime,
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'type' => explode('/', $mime)[0],
                'title' => pathinfo($path, PATHINFO_FILENAME),
                'active' => true,
                'featured' => false,
                'sort_order' => 0,
            ]);

            $count++;

            $this->line("Imported: {$path}");
        }

        $this->newLine();

        $this->info("Imported {$count} files.");
        $this->info("Skipped {$skipped} existing records.");

        return self::SUCCESS;
    }
}
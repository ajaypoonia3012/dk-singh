<?php

use App\Models\Media;
use App\Models\MediaCategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $map = MediaCategory::pluck('id', 'slug');

        Media::query()->each(function (Media $media) use ($map) {

            $slug = match ($media->folder) {
                'hero' => 'hero',
                'homepage-cards' => 'homepage-cards',
                'programs' => 'programs',
                'products' => 'products',
                'blogs' => 'blogs',
                'testimonials' => 'testimonials',
                'transformations' => 'transformations',
                'settings' => 'settings',
                default => 'general',
            };

            $media->update([
                'media_category_id' => $map[$slug] ?? null,
            ]);
        });
    }

    public function down(): void
    {
        Media::query()->update([
            'media_category_id' => null,
        ]);
    }
};

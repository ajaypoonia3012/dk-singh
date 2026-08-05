<?php

use App\Models\HeroSetting;
use App\Models\Media;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        HeroSetting::query()->each(function (HeroSetting $hero) {

            if (blank($hero->background)) {
                return;
            }

            $media = Media::where('path', $hero->background)->first();

            if ($media) {

                $hero->background_media_id = $media->id;

                $hero->save();

            }

        });
    }

    public function down(): void
    {
        HeroSetting::query()->update([
            'background_media_id' => null,
        ]);
    }
};

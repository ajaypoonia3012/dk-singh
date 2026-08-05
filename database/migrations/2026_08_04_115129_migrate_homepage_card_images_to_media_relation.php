<?php

use App\Models\HomepageCard;
use App\Models\Media;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        HomepageCard::query()->each(function (HomepageCard $card) {

            if (!$card->background_image) {
                return;
            }

            $media = Media::where('path', $card->background_image)->first();

            if (!$media) {
                return;
            }

            $card->update([
                'background_media_id' => $media->id,
            ]);
        });
    }

    public function down(): void
    {
        HomepageCard::query()->update([
            'background_media_id' => null,
        ]);
    }
};
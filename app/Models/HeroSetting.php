<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSetting extends Model
{
    protected $fillable = [

        'heading',

        'subheading',

        'button_text',

        'button_link',

        'view_button_text',

        'view_button_link',

        'background',

        'background_media_id',

        'video',

        'overlay_color',

        'overlay_opacity',

        'template',

        'enabled',

        'followers_label',
        'years_label',
        'transformations_label',

    ];

    protected $casts = [
        'enabled' => 'boolean',
        'overlay_opacity' => 'integer',
        'background_media_id' => 'integer',
    ];

    public function backgroundMedia(): BelongsTo
    {
        return $this->belongsTo(
            Media::class,
            'background_media_id'
        );
    }

    public static bool $isSyncing = false;

    protected static function booted(): void
    {
        static::saved(function (HeroSetting $hero): void {
            if (static::$isSyncing) {
                return;
            }

            static::$isSyncing = true;
            try {
                $syncData = [];
                if ($hero->wasChanged('heading') || ($hero->wasRecentlyCreated && filled($hero->heading))) {
                    $syncData['hero_title'] = $hero->heading;
                }
                if ($hero->wasChanged('subheading') || ($hero->wasRecentlyCreated && filled($hero->subheading))) {
                    $syncData['hero_subtitle'] = $hero->subheading;
                }
                if ($hero->wasChanged('button_text') || ($hero->wasRecentlyCreated && filled($hero->button_text))) {
                    $syncData['cta_button_text'] = $hero->button_text;
                }
                if ($hero->wasChanged('button_link') || ($hero->wasRecentlyCreated && filled($hero->button_link))) {
                    $syncData['cta_button_link'] = $hero->button_link;
                }
                if ($hero->wasChanged('followers_label') || ($hero->wasRecentlyCreated && filled($hero->followers_label))) {
                    $syncData['followers_label'] = $hero->followers_label;
                }
                if ($hero->wasChanged('years_label') || ($hero->wasRecentlyCreated && filled($hero->years_label))) {
                    $syncData['years_label'] = $hero->years_label;
                }
                if ($hero->wasChanged('transformations_label') || ($hero->wasRecentlyCreated && filled($hero->transformations_label))) {
                    $syncData['transformations_label'] = $hero->transformations_label;
                }

                if (! empty($syncData)) {
                    $setting = Setting::first();
                    if ($setting) {
                        $setting->update($syncData);
                    }
                }
            } finally {
                static::$isSyncing = false;
            }
        });
    }
}

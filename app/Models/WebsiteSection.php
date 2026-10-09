<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSection extends Model
{
    protected $fillable = [

        'page',

        'section',

        'title',

        'enabled',

        'sort_order',

        'settings',

        'background',

        'template',

    ];

    protected $casts = [

        'enabled' => 'boolean',

        'settings' => 'array',

    ];

    public static bool $isSyncing = false;

    protected static function booted(): void
    {
        static::saved(function (WebsiteSection $section): void {
            if (static::$isSyncing) {
                return;
            }

            if (! $section->wasChanged('enabled') && ! $section->wasRecentlyCreated) {
                return;
            }

            $theme = ThemeSetting::first();
            if (! $theme) {
                return;
            }

            $field = 'show_' . $section->section;
            if (array_key_exists($field, $theme->getAttributes()) || in_array($field, $theme->getFillable(), true)) {
                static::$isSyncing = true;
                try {
                    $theme->update([$field => (bool) $section->enabled]);
                } finally {
                    static::$isSyncing = false;
                }
            }
        });
    }
}

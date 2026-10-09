<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageCard extends Model
{
    protected $fillable = [

        'title',
        'subtitle',
        'description',
        'button_text',
        'button_link',
        'background_image',
        'background_media_id',
        'icon',
        'sort_order',
        'is_active',
        'seo_title',
        'seo_description',

    ];

    protected $casts = [

        'is_active' => 'boolean',

    ];

    public function backgroundMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'background_media_id');
    }

    public function getIconAttribute(?string $value): ?string
    {
        if ($value !== null && ! preg_match('/^\?+$/', trim($value))) {
            return $value;
        }

        return match (true) {
            $this->id === 1 || stripos($this->title ?? '', 'personal') !== false => '🏋️',
            $this->id === 2 || stripos($this->title ?? '', 'weight loss') !== false => '🔥',
            $this->id === 3 || stripos($this->title ?? '', 'muscle') !== false => '💪',
            $this->id === 4 || stripos($this->title ?? '', 'nutrition') !== false => '🥗',
            default => null,
        };
    }

    public function getSvgIconAttribute(): ?string
    {
        return match (true) {
            $this->id === 1 || stripos($this->title ?? '', 'personal') !== false => '<svg class="w-6 h-6 theme-text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
            $this->id === 2 || stripos($this->title ?? '', 'weight loss') !== false => '<svg class="w-6 h-6 theme-text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>',
            $this->id === 3 || stripos($this->title ?? '', 'muscle') !== false => '<svg class="w-6 h-6 theme-text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
            $this->id === 4 || stripos($this->title ?? '', 'nutrition') !== false => '<svg class="w-6 h-6 theme-text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>',
            default => null,
        };
    }
}

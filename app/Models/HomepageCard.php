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
}

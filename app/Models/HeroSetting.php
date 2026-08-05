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

    public function backgroundMedia(): BelongsTo
    {
        return $this->belongsTo(
            Media::class,
            'background_media_id'
        );
    }
}

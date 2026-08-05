<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
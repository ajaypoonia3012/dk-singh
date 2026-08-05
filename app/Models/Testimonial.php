<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [

        'name',
        'location',
        'profession',
        'transformation',
        'program',
        'review',

        'image',
        'media_id',

        'rating',

        'featured',
        'status',
        'sort_order',

        'seo_title',
        'seo_description',

    ];

    protected $casts = [

        'featured' => 'boolean',
        'status' => 'boolean',

    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
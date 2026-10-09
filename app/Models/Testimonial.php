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

    public function getCleanReviewAttribute(): string
    {
        $raw = $this->review ?: ($this->content ?? '');
        if (blank($raw)) {
            return '';
        }
        $decoded = html_entity_decode(strip_tags($raw), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim(preg_replace('/[\s\x{00a0}]+/u', ' ', $decoded));
    }
}

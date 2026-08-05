<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transformation extends Model
{
    protected $fillable = [

        'name',
        'slug',

        'before_image',
        'before_media_id',

        'after_image',
        'after_media_id',

        'image',

        'goal',
        'program',
        'coach',

        'before_weight',
        'after_weight',
        'weight_loss',

        'duration',

        'story',
        'review',
        'result',
        'description',

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

    public function beforeMedia(): BelongsTo
    {
        return $this->belongsTo(
            Media::class,
            'before_media_id'
        );
    }

    public function afterMedia(): BelongsTo
    {
        return $this->belongsTo(
            Media::class,
            'after_media_id'
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($transformation) {

            if (empty($transformation->slug)) {
                $transformation->slug = Str::slug($transformation->name);
            }

        });
    }
}
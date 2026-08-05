<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'content',
        'excerpt',

        'category',
        'author',
        'reading_time',

        'featured_image',

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

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($blog) {

            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }

        });
    }
}
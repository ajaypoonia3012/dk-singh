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
        'image',

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
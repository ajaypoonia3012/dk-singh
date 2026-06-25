<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkoutPlan extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'difficulty',
        'category',
        'required_access',
        'thumbnail',
        'video_type',
        'video_url',
        'video_file',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($workout) {

            if (empty($workout->slug)) {
                $workout->slug = Str::slug($workout->title);
            }

        });
    }
}
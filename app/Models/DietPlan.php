<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DietPlan extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'description',
        'content',
        'goal',
        'image',
        'price',
        'status',
'category',
'diet_type',
'required_access',

    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($dietPlan) {

            if (empty($dietPlan->slug)) {

                $dietPlan->slug = Str::slug($dietPlan->title);

            }

        });
    }
}
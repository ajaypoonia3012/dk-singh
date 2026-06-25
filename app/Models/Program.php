<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Program extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'description',
        'image',
        'category',
        'duration',
        'price',

    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($program) {

            $program->slug = Str::slug($program->title);

        });
    }
}
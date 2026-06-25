<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'description',
        'image',
        'price',
        'duration',
        'features',

'button_text',
'featured',
'status',

    ];

    protected $casts = [

        'features' => 'array',

'featured' => 'boolean',
'status' => 'boolean',

    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($service) {

            if (empty($service->slug)) {

                $service->slug = Str::slug($service->title);

            }

        });
    }
}
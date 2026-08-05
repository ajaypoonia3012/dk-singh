<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSection extends Model
{
    protected $fillable = [

        'page',

        'section',

        'title',

        'enabled',

        'sort_order',

        'settings',

        'background',

        'template',

    ];

    protected $casts = [

        'enabled' => 'boolean',

        'settings' => 'array',

    ];
}
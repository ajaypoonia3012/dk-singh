<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [

        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'duration',
        'billing_cycle',
        'badge',
        'button_text',
        'button_link',
        'access_type',
        'thumbnail',
        'sort_order',
        'features',
        'featured',
        'status'

    ];

    protected $casts = [

        'features' => 'array',
        'featured' => 'boolean',
        'status' => 'boolean',

    ];
}
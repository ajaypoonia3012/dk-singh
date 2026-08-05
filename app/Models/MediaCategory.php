<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaCategory extends Model
{
    protected $fillable = [

        'name',
        'slug',
        'icon',
        'description',
        'sort_order',
        'active',

    ];

    protected $casts = [

        'active' => 'boolean',

    ];

    public function media(): HasMany
    {
        return $this->hasMany(
            Media::class,
            'media_category_id'
        );
    }
}

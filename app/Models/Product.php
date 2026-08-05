<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [

        'name',
        'slug',
        'sku',

        'description',

        'image',
        'media_id',

        'price',

        'weight',

        'category',

        'featured',

        'status',

        'sort_order',

        'seo_title',

        'seo_description',

    ];

    protected $casts = [

        'featured' => 'boolean',
        'status' => 'boolean',
        'price' => 'decimal:2',
        'sort_order' => 'integer',

    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {

            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }

        });
    }
}
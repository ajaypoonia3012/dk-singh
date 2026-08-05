<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Program extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'media_id',
        'category',
        'duration',
        'price',
        'featured',
        'status',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'featured'   => 'boolean',
        'status'     => 'boolean',
        'price'      => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Linked Media Library item.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($program) {
            if (blank($program->slug)) {
                $program->slug = Str::slug($program->title);
            }
        });
    }
}
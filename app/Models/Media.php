<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $fillable = [
        'name',
        'file_name',
        'disk',
        'folder',
        'media_category_id',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'type',
        'alt',
        'title',
        'description',
        'tags',
        'featured',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'active' => 'boolean',
        'tags' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            MediaCategory::class,
            'media_category_id'
        );
    }

    /**
     * Full public URL.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . ltrim($this->path, '/'));
    }

    /**
     * Nice display name used by Filament selects.
     */
    public function getDisplayNameAttribute(): string
    {
        if (! empty($this->name)) {
            return $this->name;
        }

        return pathinfo($this->file_name, PATHINFO_FILENAME);
    }
}
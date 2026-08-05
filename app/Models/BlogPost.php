<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'media_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'author',
        'reading_time',
        'views',
        'featured',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'status' => 'boolean',
        'views' => 'integer',
        'reading_time' => 'integer',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            BlogCategory::class,
            'blog_category_id'
        );
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(
            Media::class,
            'media_id'
        );
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            BlogTag::class,
            'blog_post_tag'
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function getMetaTitleAttribute(): string
    {
        return $this->seo_title ?: $this->title;
    }

    public function getMetaDescriptionAttribute(): string
    {
        return $this->seo_description
            ?: ($this->excerpt ?: Str::limit(strip_tags($this->content ?? ''), 155));
    }

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post): void {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }
}

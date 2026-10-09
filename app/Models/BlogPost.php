<?php

namespace App\Models;

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
        'content_type',
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

    public function scopeType($query, string $type)
    {
        return $query->where('content_type', $type);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->content_type) {
            'exercise_guide' => 'Exercise Guide',
            'workout_guide' => 'Workout Guide',
            'product_guide' => 'Product Guide',
            'product_review' => 'Product Review',
            'comparison' => 'Comparison',
            'how_to' => 'How-To',
            'beginner_guide' => 'Beginner Guide',
            'fitness_faq' => 'Fitness FAQ',
            default => 'Article',
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }
}

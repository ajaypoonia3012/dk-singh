<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'name',
        'slug',
        'short_description',
        'exercise_category',
        'primary_muscle',
        'secondary_muscles',
        'equipment',
        'difficulty',
        'movement_pattern',
        'setup',
        'execution_steps',
        'breathing_guidance',
        'common_mistakes',
        'safety_considerations',
        'beginner_modification',
        'advanced_variation',
        'home_variation',
        'gym_variation',
        'video_url',
        'faqs',
        'featured',
        'status',
        'views',
        'seo_title',
        'seo_description',
        'meta_description',
        'canonical_url',
    ];

    protected $casts = [
        'secondary_muscles' => 'array',
        'execution_steps' => 'array',
        'common_mistakes' => 'array',
        'faqs' => 'array',
        'status' => 'boolean',
        'featured' => 'boolean',
        'views' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['category'])) {
            $query->where('exercise_category', $filters['category']);
        }

        if (!empty($filters['muscle'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('primary_muscle', $filters['muscle'])
                  ->orWhere('secondary_muscles', 'like', '%' . $filters['muscle'] . '%');
            });
        }

        if (!empty($filters['equipment'])) {
            $query->where('equipment', $filters['equipment']);
        }

        if (!empty($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }

        if (!empty($filters['search'])) {
            $s = trim($filters['search']);
            $terms = array_unique(array_filter(array_merge(
                [$s],
                explode(' ', $s),
                explode(' ', str_replace('-', ' ', $s))
            )));

            $query->where(function ($q) use ($s, $terms) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('short_description', 'like', "%{$s}%")
                  ->orWhere('primary_muscle', 'like', "%{$s}%")
                  ->orWhere('equipment', 'like', "%{$s}%");

                foreach ($terms as $term) {
                    if (strlen($term) >= 3) {
                        $q->orWhere('name', 'like', "%{$term}%")
                          ->orWhere('short_description', 'like', "%{$term}%")
                          ->orWhere('primary_muscle', 'like', "%{$term}%");
                    }
                }
            });
        }

        return $query;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->media && file_exists(public_path('storage/' . $this->media->path))) {
            return asset('storage/' . $this->media->path);
        }

        return 'https://placehold.co/800x600?text=' . urlencode($this->name);
    }

    public function getSecondaryMusclesListAttribute(): string
    {
        if (is_array($this->secondary_muscles) && !empty($this->secondary_muscles)) {
            return implode(', ', $this->secondary_muscles);
        }

        return 'None';
    }
}

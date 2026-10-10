<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Offer extends Model
{
    use SoftDeletes;

    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_cap',
        'min_order_value',
        'applicable_products_type',
        'starts_at',
        'ends_at',
        'status',
        'max_redemptions',
        'total_redemptions',
        'max_redemptions_per_customer',
        'is_featured',
        'priority',
        'allow_stacking',
        'banner_headline',
        'banner_description',
        'banner_cta',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_cap' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'max_redemptions' => 'integer',
        'total_redemptions' => 'integer',
        'max_redemptions_per_customer' => 'integer',
        'is_featured' => 'boolean',
        'priority' => 'integer',
        'allow_stacking' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Offer $offer) {
            $offer->code = Str::upper(trim((string) $offer->code));

            // Enforce that only one offer can be featured at any time
            if ($offer->is_featured) {
                static::query()
                    ->where('id', '!=', $offer->id ?? 0)
                    ->where('is_featured', true)
                    ->update(['is_featured' => false]);
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'offer_product')->withTimestamps();
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(OfferRedemption::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getComputedStatusAttribute(): string
    {
        if ($this->status === self::STATUS_DRAFT) {
            return 'draft';
        }

        if ($this->status === self::STATUS_DISABLED) {
            return 'disabled';
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'scheduled';
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'expired';
        }

        if ($this->max_redemptions !== null && $this->total_redemptions >= $this->max_redemptions) {
            return 'exhausted';
        }

        return 'active';
    }

    public function isValidNow(): bool
    {
        return $this->computed_status === 'active';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('max_redemptions')
                    ->orWhereColumn('total_redemptions', '<', 'max_redemptions');
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->active()
            ->where('is_featured', true)
            ->orderByDesc('priority')
            ->orderByDesc('id');
    }

    public function appliesToProduct(int|Product $product): bool
    {
        if ($this->applicable_products_type === 'all') {
            return true;
        }

        $productId = $product instanceof Product ? $product->id : $product;

        if ($this->relationLoaded('products')) {
            return $this->products->contains('id', $productId);
        }

        return $this->products()->where('products.id', $productId)->exists();
    }
}

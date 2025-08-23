<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'address',
        'phone',
        'latitude',
        'longitude',
        'open_time',
        'close_time',
        'image',
        'is_open',
        'is_verified',
        'rating',
        'total_reviews'
    ];

    protected $casts = [
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'is_open' => 'boolean',
        'is_verified' => 'boolean',
        'rating' => 'decimal:1',
        'total_reviews' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the outlet
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the outlet's menus
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get the outlet's ratings
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the outlet's orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the outlet's categories
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'outlet_categories');
    }

    /**
     * Get the reviews for the outlet.
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get the average rating for the outlet.
     */
    public function getAverageRatingAttribute()
    {
        return $this->rating ?? 0;
    }

    /**
     * Get the total number of reviews for the outlet.
     */
    public function getTotalReviewsAttribute()
    {
        return $this->total_reviews ?? 0;
    }

    /**
     * Get the rating stars for display.
     */
    public function getRatingStarsAttribute()
    {
        $rating = $this->rating ?? 0;
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } elseif ($i - $rating < 1) {
                $stars .= '<i class="fas fa-star-half-alt text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        
        return $stars;
    }

    /**
     * Get the rating percentage.
     */
    public function getRatingPercentageAttribute()
    {
        return ($this->rating ?? 0) / 5 * 100;
    }

    /**
     * Check if outlet has reviews.
     */
    public function getHasReviewsAttribute()
    {
        return ($this->total_reviews ?? 0) > 0;
    }

    /**
     * Scope for verified outlets
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for open outlets
     */
    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }
}

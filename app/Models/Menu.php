<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'outlet_id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'rating',
        'total_reviews'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'rating' => 'decimal:1',
        'total_reviews' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the outlet that owns the menu
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the menu's reviews
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get the menu's order items
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Calculate average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->rating ?? 0;
    }

    /**
     * Get total reviews count
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
     * Check if menu has reviews.
     */
    public function getHasReviewsAttribute()
    {
        return ($this->total_reviews ?? 0) > 0;
    }

    /**
     * Scope for available menus
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for recommended menus
     */
    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }
}

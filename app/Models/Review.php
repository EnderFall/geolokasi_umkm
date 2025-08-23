<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'comment',
        'images',
        'is_verified'
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_verified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = ['rating_stars', 'formatted_date'];

    /**
     * Get the user that wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reviewable model (outlet or menu).
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get rating stars for display
     */
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Scope a query to only include verified reviews.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include reviews with specific rating.
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope a query to only include reviews from specific date.
     */
    public function scopeFromDate($query, $date)
    {
        return $query->whereDate('created_at', '>=', $date);
    }

    /**
     * Scope a query to only include reviews until specific date.
     */
    public function scopeUntilDate($query, $date)
    {
        return $query->whereDate('created_at', '<=', $date);
    }

    /**
     * Get review summary statistics
     */
    public static function getReviewStats($reviewableType, $reviewableId)
    {
        $reviews = static::where('reviewable_type', $reviewableType)
            ->where('reviewable_id', $reviewableId);

        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating') ?? 0;
        
        $ratingDistribution = $reviews->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();

        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratingDistribution[$i])) {
                $ratingDistribution[$i] = 0;
            }
        }

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 1),
            'rating_distribution' => $ratingDistribution,
            'percentage_positive' => $totalReviews > 0 ? round(($ratingDistribution[4] + $ratingDistribution[5]) / $totalReviews * 100, 1) : 0
        ];
    }

    /**
     * Check if user can review this item
     */
    public static function canUserReview($userId, $reviewableType, $reviewableId)
    {
        // Check if user already reviewed
        $existingReview = static::where('user_id', $userId)
            ->where('reviewable_type', $reviewableType)
            ->where('reviewable_id', $reviewableId)
            ->exists();

        if ($existingReview) {
            return false;
        }

        // Check if user has completed orders
        if ($reviewableType === Outlet::class) {
            return Order::where('user_id', $userId)
                ->where('outlet_id', $reviewableId)
                ->where('status', 'delivered')
                ->exists();
        }

        if ($reviewableType === Menu::class) {
            return Order::where('user_id', $userId)
                ->whereHas('orderItems', function($query) use ($reviewableId) {
                    $query->where('menu_id', $reviewableId);
                })
                ->where('status', 'delivered')
                ->exists();
        }

        return false;
    }

    /**
     * Get review images with full URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        return collect($this->images)->map(function($image) {
            return asset('storage/' . $image);
        })->toArray();
    }

    /**
     * Get review summary for display
     */
    public function getSummaryAttribute()
    {
        if (strlen($this->comment) <= 100) {
            return $this->comment;
        }

        return substr($this->comment, 0, 100) . '...';
    }

    /**
     * Check if review has images
     */
    public function getHasImagesAttribute()
    {
        return !empty($this->images);
    }

    /**
     * Get review age in human readable format
     */
    public function getAgeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}

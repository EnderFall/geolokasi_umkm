<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'outlet_id',
        'total_amount',
        'status',
        'notes',
        'ordered_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'ordered_at' => 'datetime',
    ];

    protected $appends = ['status_color'];

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the user that made the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the outlet for the order
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the order items
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed orders
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for completed orders
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['delivered']);
    }
}

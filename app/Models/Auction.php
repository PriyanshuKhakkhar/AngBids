<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    const MAX_INCREMENT_ALLOWED = 1000.00;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'starting_price',
        'current_price',
        'image',
        'document',
        'start_time',
        'end_time',
        'status',
        'ending_soon_notified',
        'specifications',
        'cancellation_reason',
        'min_increment',
        'winner_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'starting_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'min_increment' => 'decimal:2',
        'specifications' => 'array',
    ];

    /**
     * Scope for active auctions (Approved and not ended)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('end_time', '>', now());
    }

    /**
     * Scope for live auctions (Currently open for bidding)
     */
    public function scopeLive($query)
    {
        return $query->active()
                     ->where('start_time', '<=', now());
    }

    /**
     * Scope for past auctions (Ended)
     */
    public function scopePast($query)
    {
        return $query->where('status', 'active')
                     ->where('end_time', '<=', now());
    }

    /**
     * Scope for latest auctions
     */
    public function scopeLatestFirst($query)
    {
        return $query->latest();
    }

    // Badge class for status
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'active' => 'success',
            'pending' => 'info',
            'closed' => 'secondary',
            'cancelled' => 'danger',
            default => 'warning'
    };
    }

    /**
     * Get unique bidders count
     */
    public function getUniqueBiddersCountAttribute(): int
    {
        return $this->bids()->distinct('user_id')->count('user_id');
    }

    /**
     * Get human-readable status label with time logic
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'cancelled') {
            return 'Cancelled';
        }

        if ($this->end_time && $this->end_time->isPast()) {
            return 'Ended';
        }

        if ($this->status === 'pending') {
            return 'Pending';
        }

        if ($this->status === 'closed') {
            return 'Closed';
        }

        if ($this->start_time && $this->start_time->isFuture()) {
            return 'Starting Soon';
        }

        return 'Live';
    }

    // Get owner
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    // Get images
    public function images()
    {
        return $this->hasMany(AuctionImage::class)->orderBy('sort_order');
    }

    // Get bids
    public function bids()
    {
        return $this->hasMany(Bid::class)->latest();
    }

    // Get highest bid
    public function highestBid()
    {
        return $this->bids()->first();
    }

    // Get watchlists count or check if user watchlisted it
    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }


    // Get auto bids
    public function autoBids()
    {
        return $this->hasMany(AutoBids::class, 'auction_id');
    }

    public function registrations()
    {
        return $this->hasMany(AuctionRegistration::class);
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'auction_registrations');
    }

    /**
     * Get the winner
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Finalize the auction (Select winner and close)
     */
    public function finalize(): bool
    {
        // 1. Only finalize if active and end_time has passed
        if ($this->status !== 'active' || $this->end_time->isFuture()) {
            return false;
        }

        // 2. Determine highest bidder
        $highestBid = $this->highestBid();

        // 3. Update status and winner_id
        $this->update([
            'winner_id' => $highestBid ? $highestBid->user_id : null,
            'status' => 'closed'
        ]);

        // 4. Notify winner if exists
        if ($highestBid && $highestBid->user) {
            $highestBid->user->notify(new \App\Notifications\WinnerNotification($this));
            
            // Also notify the seller
            if ($this->user) {
                $this->user->notify(new \App\Notifications\SellerAuctionSoldNotification($this));
            }
        }

        return true;
    }
}

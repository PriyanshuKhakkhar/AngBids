<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoBids extends Model
{
    protected $table = 'auto_bids';
    protected $fillable = [
        'user_id',
        'auction_id',
        'max_bid_amount',
        'active',
    ];

    protected $casts = [
        'max_bid_amount' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}

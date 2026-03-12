<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'auction_id',
        'status',
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

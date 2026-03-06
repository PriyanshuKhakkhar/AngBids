<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction;
    public $bid;

    /**
     * Create a new event instance.
     */
    public function __construct(Auction $auction, Bid $bid)
    {
        $this->auction = $auction;
        $this->bid = $bid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('auction.' . $this->auction->id);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'bid.placed';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $currentTopBid = $this->auction->bids()->first();
        $isExtended = false; // We could pass this if we alter the constructor, but it's okay, frontend can see the end_time changed.

        return [
            'auction_id' => $this->auction->id,
            'current_price' => (float)$this->auction->current_price,
            'min_increment' => (float)$this->auction->min_increment,
            'end_time' => $this->auction->end_time->toIso8601String(),
            'end_time_formatted' => \Carbon\Carbon::parse($this->auction->end_time)->format('F d, Y \a\t g:i A'),
            'winner_id' => $currentTopBid ? $currentTopBid->user_id : null,
            'winner_name' => $currentTopBid ? $currentTopBid->user->name : null,
            'winner_username' => $currentTopBid ? $currentTopBid->user->username : null,
            'bid_amount' => (float)$this->bid->amount,
        ];
    }
}

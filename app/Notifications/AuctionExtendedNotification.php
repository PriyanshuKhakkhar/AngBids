<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Auction;

class AuctionExtendedNotification extends Notification
{
    use Queueable;

    protected $auction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'auction_extended',
            'title' => 'Fair Play: Auction Extended',
            'message' => 'The auction "' . $this->auction->title . '" has been extended by 5 minutes due to late bidding.',
            'link' => route('auctions.show', $this->auction->id),
            'auction_id' => $this->auction->id,
            'new_end_time' => $this->auction->end_time->toDateTimeString(),
        ];
    }
}

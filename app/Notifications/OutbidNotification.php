<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Notification;

class OutbidNotification extends Notification
{
    use Queueable;

    protected $auction;
    protected $newPrice;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Auction $auction, $newPrice)
    {
        $this->auction = $auction;
        $this->newPrice = $newPrice;
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
     */
    public function toArray(object $notifiable): array
    {
        return [
            'auction_id' => $this->auction->id,
            'auction_title' => $this->auction->title,
            'new_price' => $this->newPrice,
            'message' => 'You have been outbid on ' . $this->auction->title,
            'link' => route('auctions.show', $this->auction->id),
        ];
    }
}

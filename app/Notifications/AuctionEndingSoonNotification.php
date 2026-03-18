<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auction;

class AuctionEndingSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $auction;

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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->subject('Act Fast: Auction Ending in 1 Hour! ⏳')
    //                 ->greeting('Hello ' . $notifiable->name . ',')
    //                 ->line('An item in your Watchlist is ending soon.')
    //                 ->line('**' . $this->auction->title . '** is closing in exactly **1 hour**! The current bid is **₹' . number_format($this->auction->current_price, 2) . '**.')
    //                 ->line("Don't miss out on winning this item. Place your final bid now before it's too late.")
    //                 ->action('Place Bid Now', route('auctions.show', $this->auction->id))
    //                 ->line('Happy Bidding!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'auction_ending_soon',
            'auction_id' => $this->auction->id,
            'message' => 'Watchlist Alert: ' . $this->auction->title . ' is ending in 1 hour! Current bid is ₹' . number_format($this->auction->current_price, 2) . '.',
            'action_url' => '/auctions/' . $this->auction->id
        ];
    }
}

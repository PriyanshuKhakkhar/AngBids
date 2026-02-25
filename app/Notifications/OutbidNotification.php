<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You have been outbid on ' . $this->auction->title)
            ->line('You were outbid on the auction: ' . $this->auction->title)
            ->line('Current price is now: ₹' . number_format($this->newPrice, 2))
            ->action('Place a Higher Bid', route('auctions.show', $this->auction->id))
            ->line('Don\'t miss out! Good luck.');
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

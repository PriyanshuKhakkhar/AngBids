<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionStartingSoonNotification extends Notification
{
    use Queueable;

    protected $auction;
    /**
     * Create a new notification instance.
     */
    public function __construct($auction)
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Auction Starting Soon: ' . $this->auction->title)
            ->line('An auction you registered for is starting in 30 minutes!')
            ->line('Auction: ' . $this->auction->title)
            ->line('Starts at: ' . $this->auction->start_time->format('h:i A'))
            ->action('View Auction', route('auctions.show', $this->auction->id))
            ->line('Good luck with your bidding!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'auction_id' => $this->auction->id,
            'auction_title' => $this->auction->title,
            'message' => 'Auction "' . $this->auction->title . '" is starting in 30 minutes!',
            'type' => 'auction_starting_soon'
        ];
    }
}

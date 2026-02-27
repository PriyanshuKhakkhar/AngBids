<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WinnerNotification extends Notification
{
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
            'auction_id' => $this->auction->id,
            'title' => 'You won an auction!',
            'message' => 'Congratulations! You won "' . $this->auction->title . '" for ₹' . number_format($this->auction->current_price, 2) . '.',
            'amount' => $this->auction->current_price,
            'link' => route('user.winning-items'),
        ];
    }
}

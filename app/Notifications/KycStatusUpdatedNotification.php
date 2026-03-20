<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Kyc;

class KycStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $kyc;

    /**
     * Create a new notification instance.
     */
    public function __construct(Kyc $kyc)
    {
        $this->kyc = $kyc;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
                    ->subject('KYC Verification Status Updated')
                    ->greeting('Hello ' . $notifiable->name . ',');

        if ($this->kyc->status === 'approved') {
            $mail->line('Congratulations! Your Identity Verification (KYC) has been approved.')
                 ->line('You can now fully participate in auctions, register for upcoming events, and place bids.')
                 ->action('Browse Auctions', url('/auctions'));
        } else {
            $mail->line('There was an issue with your Identity Verification (KYC) and it has been rejected.')
                 ->line('Reason for rejection: ' . $this->kyc->admin_note)
                 ->line('Please review the information and submit a new request with clear and accurate documents.')
                 ->action('Resubmit KYC', route('user.kyc.form'));
        }

        return $mail->line('Thank you for being part of LaraBids!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'kyc_status_updated',
            'title' => 'KYC Status Update',
            'status' => $this->kyc->status,
            'message' => $this->kyc->status === 'approved' 
                ? 'Your KYC has been approved. You can now participate in auctions!' 
                : 'Your KYC was rejected. Reason: ' . $this->kyc->admin_note,
        ];
    }
}

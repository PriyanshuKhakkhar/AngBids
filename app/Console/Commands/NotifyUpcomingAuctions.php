<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyUpcomingAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:auctions-starting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify registered users about auctions starting in 30 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming auctions starting in 30 minutes...');

        // Find auctions starting in the next 30-35 minutes
        $startTime = now()->addMinutes(30);
        $endTime = now()->addMinutes(35);

        $registrations = \App\Models\AuctionRegistration::where('starting_notified', false)
            ->whereHas('auction', function($query) use ($startTime, $endTime) {
                $query->where('status', 'pending')
                    ->whereBetween('start_time', [$startTime, $endTime]);
            })
            ->with(['user', 'auction'])
            ->get();

        if ($registrations->isEmpty()) {
            $this->info('No upcoming auctions found for notification.');
            return 0;
        }

        foreach ($registrations as $registration) {
            $user = $registration->user;
            $auction = $registration->auction;

            $user->notify(new \App\Notifications\AuctionStartingSoonNotification($auction));
            
            $registration->update(['starting_notified' => true]);

            $this->info("Notified {$user->name} about auction: {$auction->title}");
        }

        $this->info('Notifications sent successfully.');
        return 0;
    }
}

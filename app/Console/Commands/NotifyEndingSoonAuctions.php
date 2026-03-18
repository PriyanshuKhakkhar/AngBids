<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyEndingSoonAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:auctions-ending-soon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users who have items in their watchlist that are ending in 1 hour.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for active auctions ending in 1 hour...');

        // Find auctions ending in the next 60-65 minutes that haven't sent this notification
        $startTime = now()->addMinutes(60);
        $endTime = now()->addMinutes(65);

        $auctions = \App\Models\Auction::where('ending_soon_notified', false)
            ->where('status', 'active')
            ->whereBetween('end_time', [$startTime, $endTime])
            ->with(['watchlists.user'])
            ->get();

        if ($auctions->isEmpty()) {
            $this->info('No ending soon auctions found for notification.');
            return 0;
        }

        foreach ($auctions as $auction) {
            $count = 0;
            foreach ($auction->watchlists as $watchlist) {
                if ($watchlist->user) {
                    $watchlist->user->notify(new \App\Notifications\AuctionEndingSoonNotification($auction));
                    $count++;
                }
            }
            
            // Mark the auction so we don't notify again
            $auction->update(['ending_soon_notified' => true]);

            $this->info("Notified {$count} users about auction: {$auction->title}");
        }

        $this->info('Ending soon notifications sent successfully.');
        return 0;
    }
}

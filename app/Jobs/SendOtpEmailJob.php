<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginOtpMail;

class SendOtpEmailJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = 10;

    protected $email;
    protected $otp;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, int $otp)
    {
        $this->email = $email;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new LoginOtpMail($this->otp));
    }
}

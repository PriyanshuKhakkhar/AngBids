<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Jobs\SendOtpEmailJob;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    /**
     * Show OTP verification page.
     */
    public function show(Request $request)
    {
        if (!session('otp_email')) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify the OTP entered by user.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ], [
            'otp.required' => 'Please enter the verification code.',
            'otp.numeric' => 'The verification code must be a number.',
            'otp.digits' => 'The verification code must be exactly 6 digits.',
        ]);

        $sessionOtp = session('otp_code');
        $sessionEmail = session('otp_email');

        if (!$sessionOtp || !$sessionEmail) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $rateLimitKey = 'verify-otp:' . $sessionEmail;
        
        // Allow 5 attempts per 5 minutes
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            throw ValidationException::withMessages([
                'otp' => 'Too many failed attempts. Please try again in ' . $seconds . ' seconds.',
            ]);
        }

        if ($request->otp != $sessionOtp) {
            RateLimiter::hit($rateLimitKey, 300); // 5 minutes decay
            $attemptsLeft = RateLimiter::retriesLeft($rateLimitKey, 5);
            throw ValidationException::withMessages([
                'otp' => 'The provided OTP is incorrect. ' . $attemptsLeft . ' attempts left.',
            ]);
        }

        // Clear the rate limiter upon success
        RateLimiter::clear($rateLimitKey);

        // OTP is correct — mark email as verified and login the user
        $user = User::where('email', $sessionEmail)->first();

        if ($user) {
            // Mark email as verified
            $user->update(['email_verified_at' => now()]);

            Auth::login($user);

            // Clear OTP session data
            session()->forget(['otp_code', 'otp_email', 'otp_time']);
            RateLimiter::clear('resend-otp:' . $sessionEmail);

            $request->session()->regenerate();

            return redirect()->route('user.dashboard');
        }

        return redirect()->route('login')->withErrors(['email' => 'User not found. Please try again.']);
    }

    /**
     * Resend OTP via Queue Job (rate limited: 1 per minute).
     */
    public function resend(Request $request)
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $rateLimitKey = 'resend-otp:' . $email;

        // Rate limit: 1 request per minute (60 seconds)
        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->withErrors(['error' => 'Please wait ' . $seconds . ' seconds before requesting a new OTP.']);
        }

        RateLimiter::hit($rateLimitKey, 60);

        // Generate new OTP
        $otp = rand(100000, 999999);
        session(['otp_code' => $otp, 'otp_time' => now()]);

        // Dispatch Queue Job to send email in background
        SendOtpEmailJob::dispatch($email, $otp);

        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}

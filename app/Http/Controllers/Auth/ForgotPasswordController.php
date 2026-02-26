<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Jobs\SendOtpEmailJob;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;

class ForgotPasswordController extends Controller
{
    /**
     * Show the OTP verification page for forgot password.
     */
    public function showVerifyForm()
    {
        if (!session('forgot_password_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        return view('auth.verify-forgot-password-otp');
    }

    /**
     * Verify the OTP for forgot password.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ], [
            'otp.required' => 'Please enter the verification code.',
            'otp.numeric' => 'The verification code must be a number.',
            'otp.digits' => 'The verification code must be exactly 6 digits.',
        ]);

        $sessionOtp = session('forgot_password_otp');
        $sessionEmail = session('forgot_password_email');

        if (!$sessionOtp || !$sessionEmail) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $rateLimitKey = 'forgot-password-verify-otp:' . $sessionEmail;
        
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

        // Check expiration (10 minutes)
        $sessionTime = session('forgot_password_time');
        if (!$sessionTime || now()->diffInMinutes($sessionTime) >= 10) {
            session()->forget(['forgot_password_otp', 'forgot_password_time']);
            throw ValidationException::withMessages([
                'otp' => 'This verification code has expired. Please request a new one.',
            ]);
        }

        // Clear the rate limiter upon success
        RateLimiter::clear($rateLimitKey);

        // OTP is correct — mark session as verified
        session(['forgot_password_verified' => true]);

        return redirect()->route('password.reset-form');
    }

    /**
     * Resend OTP via Queue Job
     */
    public function resendOtp(Request $request)
    {
        $email = session('forgot_password_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $rateLimitKey = 'forgot-password-resend-otp:' . $email;

        // Rate limit: 1 request per minute (60 seconds)
        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->withErrors(['error' => 'Please wait ' . $seconds . ' seconds before requesting a new OTP.']);
        }

        RateLimiter::hit($rateLimitKey, 60);

        // Generate new OTP
        $otp = rand(100000, 999999);
        session(['forgot_password_otp' => $otp, 'forgot_password_time' => now()]);

        // Dispatch Queue Job to send email in background
        SendOtpEmailJob::dispatch($email, $otp);

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm()
    {
        if (!session('forgot_password_verified') || !session('forgot_password_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Unauthorized access. Please verify OTP first.']);
        }

        return view('auth.reset-password-otp');
    }

    /**
     * Update the new password
     */
    public function resetPassword(Request $request)
    {
        if (!session('forgot_password_verified') || !session('forgot_password_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Unauthorized access. Please verify OTP first.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'password.required' => 'Please enter a new password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Passwords do not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ]);

        $user = User::where('email', session('forgot_password_email'))->first();

        if ($user) {
            // Update password
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Clear session variables
            session()->forget(['forgot_password_email', 'forgot_password_otp', 'forgot_password_time', 'forgot_password_verified']);

            return redirect()->route('login')->with('status', 'Your password has been reset successfully. You can now login.');
        }

        return redirect()->route('password.request')->withErrors(['email' => 'User not found.']);
    }
}

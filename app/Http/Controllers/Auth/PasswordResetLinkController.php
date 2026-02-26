<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => __('We can\'t find a user with that email address.')]);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        session([
            'forgot_password_email' => $request->email,
            'forgot_password_otp' => $otp,
            'forgot_password_time' => now()
        ]);

        // Send OTP via Job
        \App\Jobs\SendOtpEmailJob::dispatch($request->email, $otp);

        return redirect()->route('password.verify-otp')->with('status', 'An OTP has been sent to your email address.');
    }
}

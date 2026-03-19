<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email', 'max:255', 'not_regex:/\+/']
        ], [
            'email.not_regex' => 'Please enter a valid standard email address.'
        ]);

        $email = strtolower(trim($request->email));
        $otp = rand(100000, 999999);
        
        session([
            'register_otp_' . $email => $otp,
            'register_otp_time_' . $email => now(),
        ]);

        \App\Jobs\SendOtpEmailJob::dispatch($email, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to ' . $email
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s\'-]+$/'],
            'username' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'not_regex:/\+/'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/'],
            'otp'      => ['required', 'string', 'size:6'],
        ], [
            'name.required' => 'Full name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name must not exceed 255 characters.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'username.required' => 'Username is required.',
            'username.max' => 'Username must not exceed 255 characters.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email address is already registered.',
            'email.not_regex' => 'Please enter a valid standard email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (symbol).',
            'otp.required' => 'OTP verification code is required.',
            'otp.size' => 'OTP must be exactly 6 digits.',
        ]);

        $email = strtolower(trim($request->email));
        $sessionOtp = session('register_otp_' . $email);
        $sessionTime = session('register_otp_time_' . $email);
        
        if (!$sessionOtp || $sessionOtp != $request->otp) {
            return back()->withInput()->withErrors(['otp' => 'Invalid OTP verification code.']);
        }

        if (!$sessionTime || now()->diffInMinutes($sessionTime) > 10) {
            session()->forget(['register_otp_' . $email, 'register_otp_time_' . $email]);
            return back()->withInput()->withErrors(['otp' => 'This verification code has expired. Please request a new one.']);
        }

        // OTP is correct
        session()->forget(['register_otp_' . $email, 'register_otp_time_' . $email]);

        $user = User::create([
            'name' => trim($request->name),
            'username' => strtolower(trim($request->username)),
            'email' => $email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // verified immediately
        ]);

        // Default role for new registrations
        $user->assignRole('user');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Account created and verified successfully!');
    }
}

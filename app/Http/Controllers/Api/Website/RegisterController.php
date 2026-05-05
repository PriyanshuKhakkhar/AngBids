<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Jobs\SendOtpEmailJob;

class RegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Check if an unverified user already exists with this email
        $existingUser = User::where('email', $request->email)->first();
        
        $emailRules = ['required', 'string', 'email', 'max:255'];
        
        if ($existingUser && !is_null($existingUser->email_verified_at)) {
            // If verified, enforce the unique rule as normal
            $emailRules[] = Rule::unique('users', 'email')->whereNull('deleted_at');
        } elseif (!$existingUser) {
            // If new user, enforce the unique rule
            $emailRules[] = Rule::unique('users', 'email')->whereNull('deleted_at');
        }
        // If unverified user exists, we skip the unique rule so we can "take over" the unverified record

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => $emailRules,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Derive name and username from payload
            $name = trim($request->first_name . ' ' . $request->last_name);
            
            if ($existingUser && is_null($existingUser->email_verified_at)) {
                // Update the existing unverified user
                $existingUser->update([
                    'name' => $name,
                    'password' => $request->password, // Password hashing handled by model cast
                ]);
                $user = $existingUser;
            } else {
                // Create brand new user
                $username = strtolower(explode('@', $request->email)[0]) . rand(100, 999);
                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $request->email,
                    'password' => $request->password,
                    'email_verified_at' => null,
                ]);

                // Assign default role safely
                try {
                    if (!$user->hasRole('user')) {
                        $user->assignRole('user');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Role "user" could not be assigned to user ID ' . $user->id . ': ' . $e->getMessage());
                }
            }

            // Generate OTP
            $otp = (string) rand(100000, 999999);
            
            // Store in cache for 15 minutes
            Cache::put('otp_' . $user->email, $otp, now()->addMinutes(15));
            
            // Dispatch real email job
            SendOtpEmailJob::dispatch($user->email, (int)$otp);
            \Log::info("OTP for {$user->email} is {$otp}");

            return response()->json([
                'success' => true,
                'message' => 'Registration successful. An OTP has been sent to your email.',
                'email' => $user->email
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Registration Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.'
            ], 500);
        }
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6'
        ]);

        try {
            \Log::info("OTP received for: {$request->email} - Code: {$request->otp}");
            $cachedOtp = Cache::get('otp_' . $request->email);

            if (!$cachedOtp || $cachedOtp !== $request->otp) {
                \Log::warning("Verification failed for {$request->email}: Invalid or expired OTP. Expected: {$cachedOtp}, Received: {$request->otp}");
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP code.'
                ], 400);
            }

            $user = User::where('email', $request->email)->first();
            if ($user) {
                \Log::info("User found for verification: {$user->email} (ID: {$user->id})");
                
                $user->email_verified_at = now();
                // If you have a status field, update it here. Assuming email_verified_at is the primary indicator.
                $user->save();
                
                \Log::info("User activated and email_verified_at updated: {$user->email}");

                // Generate Auth Token for immediate login
                $token = $user->createToken('auth_token')->plainTextToken;
                \Log::info("Sanctum token created for user: {$user->email}");

                Cache::forget('otp_' . $request->email);

                \Log::info("OTP Verification successful. Returning response with token and user data.");
                return response()->json([
                    'success' => true,
                    'message' => 'Account verified successfully!',
                    'token' => $token,
                    'user' => new \App\Http\Resources\UserResource($user)
                ], 200);
            } else {
                \Log::error("User not found after OTP match for: {$request->email}");
                return response()->json([
                    'success' => false,
                    'message' => 'User account not found.'
                ], 404);
            }

        } catch (\Exception $e) {
            \Log::error("OTP Verification Exception for {$request->email}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Verification error. Please try again later.'
            ], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate OTP
        $otp = (string) rand(100000, 999999);
        Cache::put('otp_' . $user->email, $otp, now()->addMinutes(15));
        
        // Dispatch real email job
        SendOtpEmailJob::dispatch($user->email, (int)$otp);
        \Log::info("Resent OTP for {$user->email} is {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'A new code has been sent to your email.'
        ], 200);
    }
}

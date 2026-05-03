<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        \Log::info("Login attempt for email: " . $request->email);
        \Log::info("Request Data: " . json_encode($request->all()));

        if(!Auth::attempt($request->only('email', 'password'))) {
            \Log::warning("Failed login attempt for email: " . $request->email);
            return response()->json([
                'message' => 'Invalid login details'
             ], 401);
        }

        $user = Auth::user();
        \Log::info("User authenticated successfully: ID " . $user->id . " - Email: " . $user->email);

        if (is_null($user->email_verified_at)) {
            Auth::logout();
            return response()->json([
                'message' => 'Please verify your email address to log in.',
                'needs_verification' => true
            ], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // Get authenticated user
    public function user(Request $request){
        return response()->json($request->user());
    }
}

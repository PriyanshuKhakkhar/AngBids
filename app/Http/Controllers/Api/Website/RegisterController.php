<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

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
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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
            $username = strtolower(explode('@', $request->email)[0]) . rand(100, 999);

            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $request->email,
                'password' => $request->password, // Handled by password => hashed cast in User model
                'email_verified_at' => now(),
            ]);

            // Assign default role safely
            try {
                if (!$user->hasRole('user')) {
                    $user->assignRole('user');
                }
            } catch (\Exception $e) {
                \Log::warning('Role "user" could not be assigned to user ID ' . $user->id . ': ' . $e->getMessage());
            }

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Registration Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.'
            ], 500);
        }
    }
}

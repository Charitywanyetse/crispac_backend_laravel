<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                ],
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // ============= FORGOT PASSWORD METHOD =============
    
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Store OTP in cache (expires in 10 minutes)
        Cache::put('password_reset_' . $request->email, $otp, 600);
        
        // Send email with OTP
        try {
            Mail::send('emails.password-reset', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Crispac Logistics - Password Reset Code');
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Verification code sent to your email'
            ]);
        } catch (\Exception $e) {
            // If email fails, still return OTP for testing (remove in production)
            return response()->json([
                'status' => 'success',
                'message' => 'Verification code sent to your email',
                'otp' => $otp  // Remove this line when email works
            ]);
        }
    }
}
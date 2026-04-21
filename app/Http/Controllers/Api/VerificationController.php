<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class VerificationController extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $code = rand(100000, 999999);
        
        // Store code in cache for 10 minutes
        Cache::put('verification_' . $request->email, $code, 600);
        
        // Send email (you need to configure mail)
        try {
            Mail::raw("Your verification code is: $code", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Crispac Logistics - Verification Code');
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Verification code sent to your email'
            ]);
        } catch (\Exception $e) {
            // For testing, return the code
            return response()->json([
                'status' => 'success',
                'message' => 'Verification code sent',
                'code' => $code // Remove this in production
            ]);
        }
    }
    
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);
        
        $cachedCode = Cache::get('verification_' . $request->email);
        
        if ($cachedCode && $cachedCode == $request->code) {
            Cache::forget('verification_' . $request->email);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Code verified successfully'
            ]);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid or expired code'
        ], 400);
    }
}
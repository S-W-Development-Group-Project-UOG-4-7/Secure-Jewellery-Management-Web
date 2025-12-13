<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    // --- 1. LOGIN STEP 1 (Check User & Send OTP) ---
    public function loginStep1(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found']);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['status' => 'error', 'message' => 'Incorrect Password']);
            }

            // Generate OTP
            $otp = rand(100000, 999999);
            $user->otp_code = $otp;
            $user->otp_expiry = Carbon::now()->addMinutes(5);
            $user->save();

            // Safe Email Sending
            try {
                Mail::raw("Your SJM Login OTP is: $otp", function ($message) use ($user) {
                    $message->to($user->email)->subject('Secure Login OTP');
                });
                $msg = "Code sent to " . $user->email;
            } catch (\Exception $e) {
                Log::error("Mail Failed: " . $e->getMessage());
                $msg = "DEV MODE: Email Failed. OTP is " . $otp;
            }

            return response()->json(['status' => 'success', 'message' => $msg]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    // --- 2. LOGIN STEP 2 (Verify OTP) ---
    public function verifyOtp(Request $request)
    {
        try {
            $user = User::where('username', $request->username)
                        ->where('otp_code', $request->otp)
                        ->where('otp_expiry', '>', Carbon::now())
                        ->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP']);
            }

            Auth::login($user);
            $user->otp_code = null;
            $user->save();

            // Redirect Logic
            $redirect = ($user->role === 'Admin') ? route('admin.dashboard') : route('customer.studio');

            return response()->json(['status' => 'success', 'redirect' => $redirect]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    // --- 3. REGISTER ---
    public function register(Request $request)
    {
        try {
            if (User::where('email', $request->email)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'Email already taken']);
            }

            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Customer',
                'is_active' => true
            ]);

            return response()->json(['status' => 'success', 'message' => 'Account created! Please login.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
        }
    }

    // --- 4. LOGOUT (Was Missing!) ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
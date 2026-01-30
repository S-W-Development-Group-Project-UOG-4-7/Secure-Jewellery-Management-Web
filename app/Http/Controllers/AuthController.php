<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        session(['otp_user_id' => $user->id]);

        $otp = $this->generateOtp($user);

        return redirect()->route('otp.show')->with('success', 'OTP sent to your email');
    }

    public function showOtp()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return back()->with('error', 'Session expired. Please login again.');
        }

        $otpLog = OtpLog::where('user_id', $userId)
            ->where('otp_code', $request->otp)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$otpLog) {
            return back()->with('error', 'Invalid OTP');
        }

        if ($otpLog->isExpired()) {
            $otpLog->update(['status' => 'expired']);
            return back()->with('error', 'OTP has expired');
        }

        $otpLog->update(['status' => 'verified']);

        Auth::loginUsingId($userId);
        session()->forget('otp_user_id');

        return redirect()->route('dashboard')->with('success', 'Login successful');
    }

    public function resendOtp()
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return back()->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);
        $this->generateOtp($user);

        return back()->with('success', 'New OTP sent to your email');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
            'role' => 'nullable|in:admin,customer,supplier,delivery',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'customer',
        ]);

        session(['otp_user_id' => $user->id]);

        $otp = $this->generateOtp($user);

        return redirect()->route('otp.show')->with('success', 'Registration successful! OTP sent to your email');
    }

    private function generateOtp($user)
    {
        OtpLog::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpLog::create([
            'user_id' => $user->id,
            'otp_code' => $otp,
            'expires_at' => now()->addMinutes(5),
            'status' => 'pending',
        ]);

        \Log::info("OTP for {$user->email}: {$otp}");

        return $otp;
    }
}

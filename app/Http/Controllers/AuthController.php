<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpLoginMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    // STEP 1: Verify Password & Email OTP
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Credentials']);
        }

        // Generate 6-digit Code
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send Email via Gmail SMTP
        try {
            Mail::to($user->email)->send(new OtpLoginMail($otp));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Email failed to send. Check SMTP settings.']);
        }

        return response()->json([
            'status' => 'otp_required',
            'username' => $user->username,
            'message' => 'Verification code sent to email.'
        ]);
    }

    // STEP 2: Verify OTP & Login
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'otp' => 'required|numeric'
        ]);

        $user = User::where('username', $request->username)->first();

        // Validation Checks
        if (!$user || $user->otp_code != $request->otp) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Security Code']);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['status' => 'error', 'message' => 'Code has expired. Login again.']);
        }

        // Success: Clear OTP and Login
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        // --- ROLE BASED REDIRECTION ---
        $redirectUrl = route('design.studio'); // Default for customer

        switch ($user->role) {
            case 'admin':
                $redirectUrl = route('admin.dashboard');
                break;
            case 'manager':
                $redirectUrl = route('manager.dashboard');
                break;
            case 'supplier':
                $redirectUrl = route('supplier.dashboard');
                break;
        }

        return response()->json([
            'status' => 'success',
            'redirect' => $redirectUrl
        ]);
    }

    // Register (No OTP needed for creation, but login will trigger it)
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name'     => $request->username,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'redirect' => route('login')
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

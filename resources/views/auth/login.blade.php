@extends('layouts.app')

@section('title', 'Login - Secure Jewellery Management')

@section('content')
<div style="max-width: 450px; margin: 80px auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 class="gold-accent" style="font-size: 32px; margin-bottom: 10px;">Welcome Back</h1>
            <p style="color: #aaa;">Secure Jewellery Management System</p>
        </div>

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Email Address</label>
                <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required value="{{ old('email') }}">
                @error('email')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="password" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Password</label>
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required>
                @error('password')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; margin-top: 10px; padding: 14px;">
                Continue to OTP
            </button>
        </form>

        <div style="margin-top: 30px; text-align: center; padding-top: 20px; border-top: 1px solid rgba(212, 175, 55, 0.2);">
            <p style="color: #888; font-size: 13px;">
                Developed by K. M. Nethmi Sanjalee<br>
                <span class="gold-accent">ALL TECHNOLOGY</span>
            </p>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'OTP Verification - Secure Jewellery Management')

@section('content')
<div style="max-width: 450px; margin: 80px auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 class="gold-accent" style="font-size: 32px; margin-bottom: 10px;">OTP Verification</h1>
            <p style="color: #aaa;">Enter the 6-digit code sent to your email</p>
        </div>

        <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="otp" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">OTP Code</label>
                <input type="text" id="otp" name="otp" class="input-field" placeholder="Enter 6-digit OTP" required maxlength="6" pattern="[0-9]{6}" style="font-size: 24px; letter-spacing: 8px; text-align: center;">
                @error('otp')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <span id="timer" style="color: #d4af37; font-weight: 600;"></span>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; padding: 14px;">
                Verify OTP
            </button>
        </form>

        <form action="{{ route('otp.resend') }}" method="POST" id="resendForm" style="margin-top: 15px;">
            @csrf
            <button type="submit" class="btn-outline-gold" style="width: 100%; padding: 12px;" id="resendBtn">
                Resend OTP
            </button>
        </form>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ route('login') }}" style="color: #888; text-decoration: none;">Back to Login</a>
        </div>
    </div>
</div>

<script>
    let timeLeft = 300;
    const timerElement = document.getElementById('timer');
    const resendBtn = document.getElementById('resendBtn');

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `OTP expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            timerElement.textContent = 'OTP expired. Please resend.';
            timerElement.style.color = '#ff9f9f';
        } else {
            timeLeft--;
            setTimeout(updateTimer, 1000);
        }
    }

    updateTimer();

    document.getElementById('otp').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection

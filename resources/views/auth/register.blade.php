@extends('layouts.app')

@section('title', 'Register - Secure Jewellery Management')

@section('content')
<div style="max-width: 550px; margin: 80px auto;">
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 class="gold-accent" style="font-size: 32px; margin-bottom: 10px;">Create Account</h1>
            <p style="color: #aaa;">Join the Secure Jewellery Management System</p>
        </div>

        <form action="{{ route('register') }}" method="POST" id="registerForm">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Full Name</label>
                <input type="text" id="name" name="name" class="input-field" placeholder="Enter your full name" required value="{{ old('name') }}">
                @error('name')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label for="email" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Email Address</label>
                    <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required value="{{ old('email') }}">
                    @error('email')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="phone" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="input-field" placeholder="Enter your phone" required value="{{ old('phone') }}">
                    @error('phone')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="role" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Account Type</label>
                <select id="role" name="role" class="input-field">
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                    <option value="delivery" {{ old('role') == 'delivery' ? 'selected' : '' }}>Delivery Personnel</option>
                </select>
                @error('role')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Password</label>
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter password (min. 8 characters)" required minlength="8">
                @error('password')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
                <small style="color: #888; display: block; margin-top: 5px;">Must be at least 8 characters long</small>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="password_confirmation" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="input-field" placeholder="Confirm your password" required minlength="8">
                <small id="passwordMatch" style="display: block; margin-top: 5px;"></small>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; margin-top: 10px; padding: 14px;">
                Register & Verify OTP
            </button>
        </form>

        <div style="margin-top: 25px; text-align: center;">
            <p style="color: #888;">Already have an account? <a href="{{ route('login') }}" style="color: #d4af37; text-decoration: none;">Login here</a></p>
        </div>

        <div style="margin-top: 30px; text-align: center; padding-top: 20px; border-top: 1px solid rgba(212, 175, 55, 0.2);">
            <p style="color: #888; font-size: 13px;">
                Developed by K. M. Nethmi Sanjalee<br>
                <span class="gold-accent">ALL TECHNOLOGY</span>
            </p>
        </div>
    </div>
</div>

<script>
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const matchMessage = document.getElementById('passwordMatch');

    function checkPasswordMatch() {
        if (confirmPassword.value === '') {
            matchMessage.textContent = '';
            return;
        }

        if (password.value === confirmPassword.value) {
            matchMessage.textContent = 'Passwords match';
            matchMessage.style.color = '#9fff9f';
        } else {
            matchMessage.textContent = 'Passwords do not match';
            matchMessage.style.color = '#ff9f9f';
        }
    }

    password.addEventListener('input', checkPasswordMatch);
    confirmPassword.addEventListener('input', checkPasswordMatch);

    document.getElementById('registerForm').addEventListener('submit', function(e) {
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });
</script>
@endsection

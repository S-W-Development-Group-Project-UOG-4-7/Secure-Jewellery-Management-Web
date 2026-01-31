@extends('layouts.app')

@section('title', 'Change Password - Secure Jewellery Management')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">Change Password</h1>

    <div class="card">
        <form action="{{ route('profile.change-password.update') }}" method="POST" id="changePasswordForm">
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="current_password" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="input-field" placeholder="Enter current password" required>
                @error('current_password')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="new_password" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">New Password</label>
                <input type="password" id="new_password" name="new_password" class="input-field" placeholder="Enter new password (min. 8 characters)" required minlength="8">
                @error('new_password')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
                <small style="color: #888; display: block; margin-top: 5px;">Password must be at least 8 characters long</small>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="new_password_confirmation" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="input-field" placeholder="Confirm new password" required minlength="8">
                <small id="passwordMatch" style="display: block; margin-top: 5px;"></small>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Change Password</button>
                <a href="{{ route('profile.show') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const matchMessage = document.getElementById('passwordMatch');

    function checkPasswordMatch() {
        if (confirmPassword.value === '') {
            matchMessage.textContent = '';
            return;
        }

        if (newPassword.value === confirmPassword.value) {
            matchMessage.textContent = 'Passwords match';
            matchMessage.style.color = '#9fff9f';
        } else {
            matchMessage.textContent = 'Passwords do not match';
            matchMessage.style.color = '#ff9f9f';
        }
    }

    newPassword.addEventListener('input', checkPasswordMatch);
    confirmPassword.addEventListener('input', checkPasswordMatch);

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        if (newPassword.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Edit User - Secure Jewellery Management')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">Edit User</h1>

    <div class="card">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Full Name</label>
                    <input type="text" id="name" name="name" class="input-field" placeholder="Enter full name" required value="{{ old('name', $user->name) }}">
                    @error('name')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="email" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Email Address</label>
                    <input type="email" id="email" name="email" class="input-field" placeholder="Enter email address" required value="{{ old('email', $user->email) }}">
                    @error('email')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="phone" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="input-field" placeholder="Enter phone number" value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="role" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Role</label>
                    <select id="role" name="role" class="input-field" required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="supplier" {{ old('role', $user->role) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="delivery" {{ old('role', $user->role) == 'delivery' ? 'selected' : '' }}>Delivery</option>
                    </select>
                    @error('role')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="border-top: 1px solid rgba(212, 175, 55, 0.2); padding-top: 20px; margin-bottom: 20px;">
                <p style="color: #888; margin-bottom: 15px;">Leave password fields empty to keep current password</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label for="password" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">New Password</label>
                        <input type="password" id="password" name="password" class="input-field" placeholder="Enter new password (optional)" minlength="8">
                        @error('password')
                            <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input-field" placeholder="Confirm new password" minlength="8">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="is_active" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Account Status</label>
                <select id="is_active" name="is_active" class="input-field" required>
                    <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

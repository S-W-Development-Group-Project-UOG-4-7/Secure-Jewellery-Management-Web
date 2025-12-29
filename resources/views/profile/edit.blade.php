@extends('layouts.app')

@section('title', 'Edit Profile - Secure Jewellery Management')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">Edit Profile</h1>

    <div class="card">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Full Name</label>
                <input type="text" id="name" name="name" class="input-field" placeholder="Enter your name" required value="{{ old('name', $user->name) }}">
                @error('name')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="email" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Email Address</label>
                <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required value="{{ old('email', $user->email) }}">
                @error('email')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Update Profile</button>
                <a href="{{ route('profile.show') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

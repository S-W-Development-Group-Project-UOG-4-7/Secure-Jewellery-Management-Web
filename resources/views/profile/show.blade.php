@extends('layouts.app')

@section('title', 'Profile - Secure Jewellery Management')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">My Profile</h1>

    <div class="card" style="margin-bottom: 20px;">
        <h2 class="gold-accent" style="margin-bottom: 20px; font-size: 24px;">Profile Information</h2>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; color: #d4af37; font-weight: 600;">Name</label>
            <p style="color: #ffffff; font-size: 16px;">{{ $user->name }}</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; color: #d4af37; font-weight: 600;">Email</label>
            <p style="color: #ffffff; font-size: 16px;">{{ $user->email }}</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; color: #d4af37; font-weight: 600;">Member Since</label>
            <p style="color: #ffffff; font-size: 16px;">{{ $user->created_at->format('F d, Y') }}</p>
        </div>

        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <a href="{{ route('profile.edit') }}" class="btn-gold">Edit Profile</a>
            <a href="{{ route('profile.change-password') }}" class="btn-outline-gold">Change Password</a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Customer - Secure Jewellery Management')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">Edit Customer</h1>

    <div class="card">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Full Name</label>
                    <input type="text" id="name" name="name" class="input-field" placeholder="Enter full name" required value="{{ old('name', $customer->name) }}">
                    @error('name')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="phone" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="input-field" placeholder="Enter phone number" required value="{{ old('phone', $customer->phone) }}">
                    @error('phone')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Email Address</label>
                <input type="email" id="email" name="email" class="input-field" placeholder="Enter email address" required value="{{ old('email', $customer->email) }}">
                @error('email')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label for="address" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Address</label>
                <textarea id="address" name="address" class="input-field" placeholder="Enter address" rows="3">{{ old('address', $customer->address) }}</textarea>
                @error('address')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="status" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Status</label>
                <select id="status" name="status" class="input-field" required>
                    <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Update Customer</button>
                <a href="{{ route('customers.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

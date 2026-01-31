@extends('layouts.app')

@section('title', 'Edit Supplier - Secure Jewellery Management')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 8px;">
            <i class="fa-solid fa-edit" style="margin-right: 12px;"></i>Edit Supplier
        </h1>
        <p style="color: #888; font-size: 14px;">Update supplier information</p>
    </div>

    <div class="card">
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                        <i class="fa-solid fa-building" style="margin-right: 6px;"></i>Supplier Name
                    </label>
                    <input type="text" id="name" name="name" class="input-field" placeholder="Enter supplier name" required value="{{ old('name', $supplier->name) }}">
                    @error('name')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="phone" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                        <i class="fa-solid fa-phone" style="margin-right: 6px;"></i>Phone Number
                    </label>
                    <input type="text" id="phone" name="phone" class="input-field" placeholder="Enter phone number" required value="{{ old('phone', $supplier->phone) }}">
                    @error('phone')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="contact_email" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                    <i class="fa-solid fa-envelope" style="margin-right: 6px;"></i>Contact Email
                </label>
                <input type="email" id="contact_email" name="contact_email" class="input-field" placeholder="Enter email address" required value="{{ old('contact_email', $supplier->contact_email) }}">
                @error('contact_email')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="address" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">
                    <i class="fa-solid fa-location-dot" style="margin-right: 6px;"></i>Address
                </label>
                <textarea id="address" name="address" class="input-field" placeholder="Enter supplier address" rows="3">{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">
                    <i class="fa-solid fa-save" style="margin-right: 8px;"></i>Update Supplier
                </button>
                <a href="{{ route('suppliers.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">
                    <i class="fa-solid fa-times" style="margin-right: 8px;"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@extends('layouts.app')

@section('title', 'Edit Locker - Secure Jewellery Management')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">Edit Locker</h1>

    <div class="card">
        <form action="{{ route('lockers.update', $locker) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="locker_number" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Locker Number</label>
                    <input type="text" id="locker_number" name="locker_number" class="input-field" placeholder="Enter locker number" required value="{{ old('locker_number', $locker->locker_number) }}">
                    @error('locker_number')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="location" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Location</label>
                    <input type="text" id="location" name="location" class="input-field" placeholder="Enter location" required value="{{ old('location', $locker->location) }}">
                    @error('location')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="status" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Status</label>
                <select id="status" name="status" class="input-field" required>
                    <option value="available" {{ old('status', $locker->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ old('status', $locker->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ old('status', $locker->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Update Locker</button>
                <a href="{{ route('lockers.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

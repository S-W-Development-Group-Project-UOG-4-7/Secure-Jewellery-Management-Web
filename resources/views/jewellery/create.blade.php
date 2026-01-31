@extends('layouts.app')

@section('title', 'Add Jewellery - Secure Jewellery Management')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">Add New Jewellery Item</h1>

    <div class="card">
        <form action="{{ route('jewellery.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Item Name</label>
                    <input type="text" id="name" name="name" class="input-field" placeholder="Enter item name" required value="{{ old('name') }}">
                    @error('name')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="type" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Type</label>
                    <select id="type" name="type" class="input-field" required>
                        <option value="">Select Type</option>
                        <option value="Ring" {{ old('type') == 'Ring' ? 'selected' : '' }}>Ring</option>
                        <option value="Necklace" {{ old('type') == 'Necklace' ? 'selected' : '' }}>Necklace</option>
                        <option value="Bracelet" {{ old('type') == 'Bracelet' ? 'selected' : '' }}>Bracelet</option>
                        <option value="Earrings" {{ old('type') == 'Earrings' ? 'selected' : '' }}>Earrings</option>
                        <option value="Pendant" {{ old('type') == 'Pendant' ? 'selected' : '' }}>Pendant</option>
                        <option value="Brooch" {{ old('type') == 'Brooch' ? 'selected' : '' }}>Brooch</option>
                        <option value="Watch" {{ old('type') == 'Watch' ? 'selected' : '' }}>Watch</option>
                    </select>
                    @error('type')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="metal" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Metal</label>
                    <select id="metal" name="metal" class="input-field" required>
                        <option value="">Select Metal</option>
                        <option value="Gold" {{ old('metal') == 'Gold' ? 'selected' : '' }}>Gold</option>
                        <option value="Silver" {{ old('metal') == 'Silver' ? 'selected' : '' }}>Silver</option>
                        <option value="Platinum" {{ old('metal') == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                        <option value="Rose Gold" {{ old('metal') == 'Rose Gold' ? 'selected' : '' }}>Rose Gold</option>
                        <option value="White Gold" {{ old('metal') == 'White Gold' ? 'selected' : '' }}>White Gold</option>
                    </select>
                    @error('metal')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="weight" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Weight (grams)</label>
                    <input type="number" id="weight" name="weight" class="input-field" placeholder="0.00" step="0.01" min="0.01" required value="{{ old('weight') }}">
                    @error('weight')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="value" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Value ($)</label>
                    <input type="number" id="value" name="value" class="input-field" placeholder="0.00" step="0.01" min="0" required value="{{ old('value') }}">
                    @error('value')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="status" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Status</label>
                <select id="status" name="status" class="input-field" required>
                    <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="in_locker" {{ old('status') == 'in_locker' ? 'selected' : '' }}>In Locker</option>
                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
                @error('status')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Create Item</button>
                <a href="{{ route('jewellery.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Jewellery - Secure Jewellery Management')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 30px;">Edit Jewellery Item</h1>

    <div class="card">
        <form action="{{ route('jewellery.update', $jewellery) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Item Name</label>
                    <input type="text" id="name" name="name" class="input-field" placeholder="Enter item name" required value="{{ old('name', $jewellery->name) }}">
                    @error('name')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="type" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Type</label>
                    <select id="type" name="type" class="input-field" required>
                        <option value="">Select Type</option>
                        <option value="Ring" {{ old('type', $jewellery->type) == 'Ring' ? 'selected' : '' }}>Ring</option>
                        <option value="Necklace" {{ old('type', $jewellery->type) == 'Necklace' ? 'selected' : '' }}>Necklace</option>
                        <option value="Bracelet" {{ old('type', $jewellery->type) == 'Bracelet' ? 'selected' : '' }}>Bracelet</option>
                        <option value="Earrings" {{ old('type', $jewellery->type) == 'Earrings' ? 'selected' : '' }}>Earrings</option>
                        <option value="Pendant" {{ old('type', $jewellery->type) == 'Pendant' ? 'selected' : '' }}>Pendant</option>
                        <option value="Brooch" {{ old('type', $jewellery->type) == 'Brooch' ? 'selected' : '' }}>Brooch</option>
                        <option value="Watch" {{ old('type', $jewellery->type) == 'Watch' ? 'selected' : '' }}>Watch</option>
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
                        <option value="Gold" {{ old('metal', $jewellery->metal) == 'Gold' ? 'selected' : '' }}>Gold</option>
                        <option value="Silver" {{ old('metal', $jewellery->metal) == 'Silver' ? 'selected' : '' }}>Silver</option>
                        <option value="Platinum" {{ old('metal', $jewellery->metal) == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                        <option value="Rose Gold" {{ old('metal', $jewellery->metal) == 'Rose Gold' ? 'selected' : '' }}>Rose Gold</option>
                        <option value="White Gold" {{ old('metal', $jewellery->metal) == 'White Gold' ? 'selected' : '' }}>White Gold</option>
                    </select>
                    @error('metal')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="weight" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Weight (grams)</label>
                    <input type="number" id="weight" name="weight" class="input-field" placeholder="0.00" step="0.01" min="0.01" required value="{{ old('weight', $jewellery->weight) }}">
                    @error('weight')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="value" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Value ($)</label>
                    <input type="number" id="value" name="value" class="input-field" placeholder="0.00" step="0.01" min="0" required value="{{ old('value', $jewellery->value) }}">
                    @error('value')
                        <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="status" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Status</label>
                <select id="status" name="status" class="input-field" required>
                    <option value="available" {{ old('status', $jewellery->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="in_locker" {{ old('status', $jewellery->status) == 'in_locker' ? 'selected' : '' }}>In Locker</option>
                    <option value="sold" {{ old('status', $jewellery->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
                @error('status')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Update Item</button>
                <a href="{{ route('jewellery.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Before Storage Verification - Secure Jewellery Management')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 10px;">Before Storage Verification</h1>
    <p style="color: #888; margin-bottom: 30px;">Step 1 of 2: Document the condition and items before placing them in the locker</p>

    <div class="card">
        <form action="{{ route('locker-verification.before.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 25px;">
                <label for="locker_id" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Select Locker</label>
                <select id="locker_id" name="locker_id" class="input-field" required>
                    <option value="">Choose a locker...</option>
                    @foreach($lockers as $locker)
                        <option value="{{ $locker->id }}" {{ old('locker_id') == $locker->id ? 'selected' : '' }}>
                            {{ $locker->locker_number }} - {{ $locker->location }}
                        </option>
                    @endforeach
                </select>
                @error('locker_id')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Select Jewellery Items (Multiple)</label>
                <div style="background: rgba(26, 26, 26, 0.8); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; padding: 15px; max-height: 300px; overflow-y: auto;">
                    @if($jewellery->count() > 0)
                        @foreach($jewellery as $item)
                            <label style="display: flex; align-items: center; padding: 10px; margin-bottom: 5px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='rgba(212, 175, 55, 0.1)'" onmouseout="this.style.background='transparent'">
                                <input type="checkbox" name="jewellery_ids[]" value="{{ $item->id }}" style="margin-right: 12px; width: 18px; height: 18px;" {{ in_array($item->id, old('jewellery_ids', [])) ? 'checked' : '' }}>
                                <span style="flex: 1; color: #ffffff;">{{ $item->name }} - {{ $item->type }} ({{ $item->metal }})</span>
                                <span style="color: #d4af37; font-size: 12px;">${{ number_format($item->value, 2) }}</span>
                            </label>
                        @endforeach
                    @else
                        <p style="color: #888; text-align: center; padding: 20px;">No available jewellery items. Please add jewellery first.</p>
                    @endif
                </div>
                @error('jewellery_ids')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="before_notes" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Before-Storage Notes</label>
                <textarea id="before_notes" name="before_notes" class="input-field" rows="4" placeholder="Document the condition of items before storage...">{{ old('before_notes') }}</textarea>
                @error('before_notes')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="before_image" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Before-Storage Image (Optional)</label>
                <input type="file" id="before_image" name="before_image" class="input-field" accept="image/*" style="padding: 10px;" onchange="previewImage(event, 'before-preview')">
                <small style="color: #888; display: block; margin-top: 5px;">Max 5MB (JPG, PNG)</small>
                @error('before_image')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
                <div id="before-preview" style="margin-top: 15px;"></div>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Record & Continue to After-Storage</button>
                <a href="{{ route('locker-verification.index') }}" class="btn-outline-gold" style="flex: 1; text-align: center; padding: 12px 30px; text-decoration: none; display: block;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '300px';
                img.style.maxHeight = '300px';
                img.style.border = '2px solid #d4af37';
                img.style.borderRadius = '8px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection

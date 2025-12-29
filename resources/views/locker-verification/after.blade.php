@extends('layouts.app')

@section('title', 'After Storage Verification - Secure Jewellery Management')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <h1 class="gold-accent" style="font-size: 36px; margin-bottom: 10px;">After Storage Verification</h1>
    <p style="color: #888; margin-bottom: 30px;">Step 2 of 2: Verify the condition after placing items in the locker</p>

    <div class="card" style="margin-bottom: 20px;">
        <h2 class="gold-accent" style="font-size: 24px; margin-bottom: 20px;">Before-Storage Information</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 5px;">Locker</label>
                <p style="color: #ffffff; margin: 0;">{{ $verification->locker->locker_number }} - {{ $verification->locker->location }}</p>
            </div>
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 5px;">Verified By</label>
                <p style="color: #ffffff; margin: 0;">{{ $verification->verifiedBy->name }}</p>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 8px;">Items in Locker ({{ $verification->jewelleryItems->count() }})</label>
            <div style="background: rgba(26, 26, 26, 0.8); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; padding: 15px;">
                @foreach($verification->jewelleryItems as $item)
                    <div style="padding: 8px 0; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                        <span style="color: #ffffff;">{{ $item->name }} - {{ $item->type }} ({{ $item->metal }})</span>
                        <span style="color: #d4af37; float: right;">${{ number_format($item->value, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        @if($verification->before_notes)
        <div style="margin-bottom: 20px;">
            <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 5px;">Before-Storage Notes</label>
            <p style="color: #ffffff; background: rgba(26, 26, 26, 0.8); padding: 12px; border-radius: 6px; border: 1px solid rgba(212, 175, 55, 0.3);">{{ $verification->before_notes }}</p>
        </div>
        @endif

        @if($verification->before_image)
        <div>
            <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 8px;">Before-Storage Image</label>
            <img src="{{ Storage::url($verification->before_image) }}" alt="Before Storage" style="max-width: 400px; max-height: 400px; border: 2px solid #d4af37; border-radius: 8px;">
        </div>
        @endif
    </div>

    <div class="card">
        <form action="{{ route('locker-verification.after.store', $verification) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 25px;">
                <label for="after_notes" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">After-Storage Notes</label>
                <textarea id="after_notes" name="after_notes" class="input-field" rows="4" placeholder="Document the condition of items after storage and any observations...">{{ old('after_notes') }}</textarea>
                @error('after_notes')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="after_image" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">After-Storage Image (Required)</label>
                <input type="file" id="after_image" name="after_image" class="input-field" accept="image/*" style="padding: 10px;" onchange="previewImage(event, 'after-preview')" required>
                <small style="color: #888; display: block; margin-top: 5px;">Max 5MB (JPG, PNG)</small>
                @error('after_image')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
                <div id="after-preview" style="margin-top: 15px;"></div>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="result_status" style="display: block; margin-bottom: 8px; color: #d4af37; font-weight: 600;">Verification Result</label>
                <select id="result_status" name="result_status" class="input-field" required>
                    <option value="">Select result...</option>
                    <option value="pass" {{ old('result_status') == 'pass' ? 'selected' : '' }}>Pass - Items secured successfully</option>
                    <option value="fail" {{ old('result_status') == 'fail' ? 'selected' : '' }}>Fail - Issues detected</option>
                    <option value="flagged" {{ old('result_status') == 'flagged' ? 'selected' : '' }}>Flagged - Requires further review</option>
                </select>
                @error('result_status')
                    <small style="color: #ff9f9f; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-gold" style="flex: 1;">Complete Verification</button>
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

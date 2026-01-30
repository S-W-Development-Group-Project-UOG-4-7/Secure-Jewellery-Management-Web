@extends('layouts.app')

@section('title', 'Verification Results - Secure Jewellery Management')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="gold-accent" style="font-size: 36px;">Verification Results</h1>
        <a href="{{ route('locker-verification.index') }}" class="btn-outline-gold">Back to List</a>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="gold-accent" style="font-size: 24px; margin: 0;">Verification Summary</h2>
            @if($verification->status == 'pass')
                <span class="badge badge-active" style="font-size: 16px; padding: 8px 20px;">PASS</span>
            @elseif($verification->status == 'fail')
                <span class="badge badge-inactive" style="font-size: 16px; padding: 8px 20px;">FAIL</span>
            @else
                <span class="badge" style="background: rgba(255, 152, 0, 0.3); color: #ff9800; font-size: 16px; padding: 8px 20px;">FLAGGED</span>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Locker</label>
                <p style="color: #ffffff; margin: 0; font-size: 16px; font-weight: 600;">{{ $verification->locker->locker_number }}</p>
                <p style="color: #888; margin: 0; font-size: 12px;">{{ $verification->locker->location }}</p>
            </div>
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Verified By</label>
                <p style="color: #ffffff; margin: 0; font-size: 16px;">{{ $verification->verifiedBy->name }}</p>
            </div>
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Started</label>
                <p style="color: #ffffff; margin: 0; font-size: 16px;">{{ $verification->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Completed</label>
                <p style="color: #ffffff; margin: 0; font-size: 16px;">{{ $verification->completed_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <h2 class="gold-accent" style="font-size: 24px; margin-bottom: 20px;">Items in Locker ({{ $verification->jewelleryItems->count() }})</h2>
        <div style="background: rgba(26, 26, 26, 0.8); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 6px; padding: 15px;">
            @foreach($verification->jewelleryItems as $item)
                <div style="padding: 12px 0; border-bottom: 1px solid rgba(212, 175, 55, 0.1); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="color: #ffffff; font-weight: 600;">{{ $item->name }}</span>
                        <span style="color: #888; margin-left: 15px;">{{ $item->type }} | {{ $item->metal }} | {{ number_format($item->weight, 2) }}g</span>
                    </div>
                    <span style="color: #d4af37; font-weight: 600;">${{ number_format($item->value, 2) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="card">
            <h2 class="gold-accent" style="font-size: 24px; margin-bottom: 20px;">Before Storage</h2>

            @if($verification->before_notes)
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 8px;">Notes</label>
                <p style="color: #ffffff; background: rgba(26, 26, 26, 0.8); padding: 12px; border-radius: 6px; border: 1px solid rgba(212, 175, 55, 0.3); margin: 0;">{{ $verification->before_notes }}</p>
            </div>
            @endif

            @if($verification->before_image)
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 8px;">Proof Image</label>
                <img src="{{ Storage::url($verification->before_image) }}" alt="Before Storage" style="width: 100%; max-height: 400px; object-fit: contain; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer;" onclick="window.open(this.src)">
                <small style="color: #888; display: block; margin-top: 5px;">Click to view full size</small>
            </div>
            @else
            <p style="color: #888; font-style: italic;">No image provided</p>
            @endif
        </div>

        <div class="card">
            <h2 class="gold-accent" style="font-size: 24px; margin-bottom: 20px;">After Storage</h2>

            @if($verification->after_notes)
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 8px;">Notes</label>
                <p style="color: #ffffff; background: rgba(26, 26, 26, 0.8); padding: 12px; border-radius: 6px; border: 1px solid rgba(212, 175, 55, 0.3); margin: 0;">{{ $verification->after_notes }}</p>
            </div>
            @endif

            @if($verification->after_image)
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 8px;">Proof Image</label>
                <img src="{{ Storage::url($verification->after_image) }}" alt="After Storage" style="width: 100%; max-height: 400px; object-fit: contain; border: 2px solid #d4af37; border-radius: 8px; cursor: pointer;" onclick="window.open(this.src)">
                <small style="color: #888; display: block; margin-top: 5px;">Click to view full size</small>
            </div>
            @else
            <p style="color: #888; font-style: italic;">No image provided</p>
            @endif
        </div>
    </div>
</div>
@endsection
